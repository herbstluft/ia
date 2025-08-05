import torch
import collections
import os
import sys

# 🔧 Importar clases necesarias para deserialización segura
from TTS.utils.radam import RAdam
from TTS.tts.configs.xtts_config import XttsConfig
from TTS.tts.models.xtts import XttsAudioConfig, XttsArgs
from TTS.config.shared_configs import BaseDatasetConfig
from TTS.api import TTS

# ✅ Aceptar términos de uso de Coqui TTS
os.environ["COQUI_TOS_AGREED"] = "1"

# ✅ Permitir objetos personalizados para deserialización segura
torch.serialization.add_safe_globals([
    RAdam,
    dict,
    collections.defaultdict,
    XttsConfig,
    XttsAudioConfig,
    BaseDatasetConfig,
    XttsArgs
])

# ✅ Configurar modelo
model_name = "tts_models/multilingual/multi-dataset/xtts_v2"
tts = TTS(model_name=model_name)

# ✅ Obtener texto desde argumentos
if len(sys.argv) < 2:
    print("Uso: python speak.py \"Texto a decir\"")
    sys.exit(1)

text = sys.argv[1]

# ✅ Sintetizar voz
tts.tts_to_file(
    text=text,
    speaker_wav=os.path.abspath("voz.wav"),
    language="es",
    file_path="output.wav"
)
