//F6 - Evan Yuvraj Munshi

export class VoiceInteraction {
    constructor() {
        this.synthesis = window.speechSynthesis;
        this.isHoverMode = false;
        this.currentUtterance = null;
        this.hoveredElement = null;

        this.handleMouseOver = this.handleMouseOver.bind(this);
        this.handleMouseOut = this.handleMouseOut.bind(this);

        this.createUI();
    }

    createUI() {
        if (document.getElementById('voice-widget')) return;

        const container = document.createElement('div');
        container.id = 'voice-widget';
        container.className = 'fixed bottom-6 left-6 z-50 flex flex-col items-start gap-2';

        const toggleBtn = document.createElement('button');
        toggleBtn.id = 'voice-toggle-btn';
        toggleBtn.className = 'w-16 h-16 bg-slate-800 hover:bg-slate-700 text-white rounded-full shadow-2xl flex items-center justify-center transition-all transform hover:scale-105 focus:outline-none border-4 border-transparent';
        toggleBtn.setAttribute('aria-label', 'Toggle Hover Reader');
        toggleBtn.title = "Enable Hover Reader";

        toggleBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
            </svg>
        `;

        toggleBtn.onclick = () => this.toggleHoverMode();

        container.appendChild(toggleBtn);
        document.body.appendChild(container);

        const style = document.createElement('style');
        style.textContent = `
            .voice-reading-highlight {
                outline: 2px solid #3b82f6 !important;
                background-color: rgba(59, 130, 246, 0.1) !important;
                cursor: help !important;
            }
        `;
        document.head.appendChild(style);
    }

    toggleHoverMode() {
        this.isHoverMode = !this.isHoverMode;
        const btn = document.getElementById('voice-toggle-btn');

        if (this.isHoverMode) {
            btn.classList.remove('bg-slate-800', 'hover:bg-slate-700');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-500', 'border-blue-300');
            btn.title = "Disable Hover Reader";
            this.speak("Hover reader enabled. Move your mouse over text to hear it.");

            document.body.addEventListener('mouseover', this.handleMouseOver);
            document.body.addEventListener('mouseout', this.handleMouseOut);
        } else {
            btn.classList.add('bg-slate-800', 'hover:bg-slate-700');
            btn.classList.remove('bg-blue-600', 'hover:bg-blue-500', 'border-blue-300');
            btn.title = "Enable Hover Reader";
            this.speak("Hover reader disabled.");

            document.body.removeEventListener('mouseover', this.handleMouseOver);
            document.body.removeEventListener('mouseout', this.handleMouseOut);
            this.stopSpeaking();
            this.removeHighlight();
        }
    }

    suspend() {
        this.isSuspended = true;
        this.stopSpeaking();
        this.removeHighlight();
    }

    resume() {
        this.isSuspended = false;
    }

    handleMouseOver(event) {
        if (!this.isHoverMode || this.isSuspended) return;

        const target = event.target;

        if (target.closest('#voice-widget')) return;

        let text = "";

        if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA') {
            text = target.value || target.placeholder || "";
            if (target.labels && target.labels.length > 0) {
                text = target.labels[0].innerText + ". " + text;
            }
        } else {
            const validTags = ['P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'SPAN', 'A', 'BUTTON', 'LI', 'LABEL', 'TD', 'TH', 'STRONG', 'EM', 'B', 'I'];

            if (validTags.includes(target.tagName) || (target.innerText && target.children.length === 0)) {
                text = target.innerText;
            }
        }

        text = text.trim();

        if (text && text.length > 0) {
            if (this.hoveredElement === target) return;

            this.removeHighlight();
            this.hoveredElement = target;
            this.hoveredElement.classList.add('voice-reading-highlight');

            if (this.debounceTimer) clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.speak(text);
            }, 200);
        }
    }

    handleMouseOut(event) {
        if (event.target === this.hoveredElement) {
            this.removeHighlight();
            this.hoveredElement = null;
        }
    }

    removeHighlight() {
        if (this.hoveredElement) {
            this.hoveredElement.classList.remove('voice-reading-highlight');
        }
    }

    speak(text) {
        if (this.isSuspended) return;

        if (this.synthesis.speaking) {
            this.synthesis.cancel();
        }

        if (text.length > 300) {
            text = text.substring(0, 300) + "...";
        }

        const utterance = new SpeechSynthesisUtterance(text);
        this.synthesis.speak(utterance);
    }

    stopSpeaking() {
        this.synthesis.cancel();
    }
}

window.addEventListener('DOMContentLoaded', () => {
    if (window.ableLinkIsDisabled && !window.VoiceAssistant) {
        window.VoiceAssistant = new VoiceInteraction();
    } else if (!window.ableLinkIsDisabled) {
        console.log('[VoiceInteraction] Hover Reader disabled for non-disabled users');
    }
});
