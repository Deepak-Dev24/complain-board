import sys
import json
import os
from openai import OpenAI

if len(sys.argv) != 2:
    print("Usage: python analyze.py <transcript_path>")
    sys.exit(1)

client = OpenAI(api_key=os.getenv("OPENAI_API_KEY"))

transcript_path = sys.argv[1]

with open(transcript_path, encoding="utf-8") as f:
    transcript = f.read()

prompt = f"""
You are analyzing a noisy, multilingual phone call transcript.
The text may contain Hindi, Urdu, English, Tamil, or mixed words.

Your task:
Extract structured information clearly.

Fields:
- complaint_number (string or null)
- name (string or null)
- problem (short description)
- village (string or null)
- city (string or null)
- date_requested (YYYY-MM-DD or null)
- summary (2–3 clean sentences)

Rules:
- If information is not clearly mentioned, use null
- Fix language noise and repetitions
- Understand intent, not exact words
- Return ONLY valid JSON
- No explanations, no markdown

Transcript:
{transcript}
"""

response = client.responses.create(
    model="gpt-4.1",
    input=prompt
)

raw = response.output_text.strip()

try:
    json.loads(raw)
    print(raw)
except json.JSONDecodeError:
    print(json.dumps({
        "complaint_number": None,
        "name": None,
        "problem": None,
        "village": None,
        "city": None,
        "date_requested": None,
        "summary": raw[:500]
    }))
