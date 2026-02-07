import whisper
import sys
from pathlib import Path

if len(sys.argv) != 3:
    print("Usage: python transcribe.py <audio_path> <output_path>")
    sys.exit(1)

audio_path = Path(sys.argv[1])
output_path = Path(sys.argv[2])

if not audio_path.exists():
    print("Audio file not found")
    sys.exit(1)

model = whisper.load_model("base")

result = model.transcribe(str(audio_path), task="translate")

output_path.write_text(result["text"], encoding="utf-8")

print("Transcription completed")
