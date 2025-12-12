//F6 - Evan Yuvraj Munshi
import './bootstrap';
import Alpine from 'alpinejs';
import VoiceInteraction from './voice-interaction';

window.Alpine = Alpine;

Alpine.start();

// F6 - Voice Interaction System (Evan Yuvraj Munshi)
document.addEventListener('DOMContentLoaded', () => {
    if (window.ableLinkIsDisabled) {
        new VoiceInteraction();
    }
});
