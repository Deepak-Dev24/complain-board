import os
import subprocess
import json
from db import get_db

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
PROJECT_ROOT = os.path.abspath(os.path.join(BASE_DIR, ".."))

TRANSCRIPTS_DIR = os.path.join(PROJECT_ROOT, "transcripts")

TRANSCRIBE_PY = os.path.join(BASE_DIR, "transcribe.py")
ANALYZE_PY = os.path.join(BASE_DIR, "analyze.py")

os.makedirs(TRANSCRIPTS_DIR, exist_ok=True)

print("AI WORKER STARTED")

conn = get_db()
cur = conn.cursor(dictionary=True)

# 1️⃣ Fetch calls needing processing
cur.execute("""
    SELECT
        cr.call_uuid,
        cr.recording_path,
        ca.transcript_done,
        ca.transcript_path
    FROM call_records cr
    LEFT JOIN call_analysis ca ON ca.call_uuid = cr.call_uuid
    WHERE cr.recording_downloaded = 1
      AND (ca.analysis_done IS NULL OR ca.analysis_done = 0);
""")

rows = cur.fetchall()

for row in rows:
    call_uuid = row["call_uuid"]
    audio_path = os.path.join(PROJECT_ROOT, row["recording_path"])
    transcript_path = os.path.join(TRANSCRIPTS_DIR, f"{call_uuid}.txt")

    # ---------------- TRANSCRIBE (only if needed) ----------------
    if not row["transcript_done"]:
        print(f"▶ Transcribing {call_uuid}")

        t = subprocess.run(
            ["python", TRANSCRIBE_PY, audio_path, transcript_path],
            capture_output=True,
            text=True
        )

        if t.returncode != 0 or not os.path.exists(transcript_path):
            print(f"❌ Transcription failed: {call_uuid}")
            continue

        transcript_text = open(transcript_path, encoding="utf-8").read()

        cur.execute("""
            INSERT INTO call_analysis (
                call_uuid,
                transcript,
                transcript_path,
                transcript_done,
                status
            ) VALUES (%s,%s,%s,1,'NEW')
            ON DUPLICATE KEY UPDATE
                transcript = VALUES(transcript),
                transcript_path = VALUES(transcript_path),
                transcript_done = 1
        """, (
            call_uuid,
            transcript_text,
            f"transcripts/{call_uuid}.txt"
        ))
        conn.commit()
    else:
        transcript_path = os.path.join(PROJECT_ROOT, row["transcript_path"])
        transcript_text = open(transcript_path, encoding="utf-8").read()
        print(f"▶ Using existing transcript {call_uuid}")

    # ---------------- ANALYZE ----------------
    print(f"▶ Analyzing {call_uuid}")

    a = subprocess.run(
        ["python", ANALYZE_PY, transcript_path],
        capture_output=True,
        text=True
    )

    if a.returncode != 0 or not a.stdout.strip().startswith("{"):
        cur.execute("""
            UPDATE call_analysis
            SET status = 'FAILED',
                analysis_done = 0
            WHERE call_uuid = %s
        """, (call_uuid,))
        conn.commit()

        print(f"❌ Analysis failed: {call_uuid}")
        continue

    data = json.loads(a.stdout.strip())

    cur.execute("""
        UPDATE call_analysis
        SET
            complaint_no = %s,
            name = %s,
            problem = %s,
            village = %s,
            city = %s,
            date_requested = %s,
            summary = %s,
            analysis_done = 1,
            status = 'NEW'
        WHERE call_uuid = %s
    """, (
        data.get("complaint_number"),
        data.get("name"),
        data.get("problem"),
        data.get("village"),
        data.get("city"),
        data.get("date_requested"),
        data.get("summary"),
        call_uuid
    ))
    conn.commit()

    print(f"✔ Done {call_uuid}")

cur.close()
conn.close()

print("AI WORKER FINISHED")
