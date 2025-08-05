export function useSpeech() {
  const speak = (text, options = {}) => {
    if (!window.responsiveVoice) {
      console.warn("ResponsiveVoice no está cargado.");
      return;
    }

    const defaultOptions = {
      pitch: 1.0,
      rate: 1.1,
      volume: 1,
    };

    responsiveVoice.speak(
      text,
      "Spanish Latin American Female",
      { ...defaultOptions, ...options }
    );
  };

  const cancelSpeech = () => {
    if (window.responsiveVoice && window.responsiveVoice.isPlaying()) {
      window.responsiveVoice.cancel();
    }
  };

  return { speak, cancelSpeech };
}
