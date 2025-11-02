import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// =========================================
// CHAT AUTO-SCROLL
// =========================================
document.addEventListener('DOMContentLoaded', function () {
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
        // Scroll to the bottom of the chat
        chatContainer.scrollTop = chatContainer.scrollHeight;

        // Auto-expand text area
        const textarea = document.querySelector('.chat-reply-form textarea');
        if (textarea) {
            textarea.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    }
});

// =========================================
// DARK MODE TOGGLE
// =========================================
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('theme-toggle-checkbox');
    const body = document.body;
    const sunIcon = document.querySelector('.theme-toggle-icon:first-of-type');
    const moonIcon = document.querySelector('.theme-toggle-icon:last-of-type');

    // Function to apply the theme
    function applyTheme(theme) {
        if (theme === 'dark') {
            body.classList.add('dark');
            toggle.checked = true;
            sunIcon.style.opacity = '0.5';
            moonIcon.style.opacity = '1';
        } else {
            body.classList.remove('dark');
            toggle.checked = false;
            sunIcon.style.opacity = '1';
            moonIcon.style.opacity = '0.5';
        }
    }

    // Check saved preference on load
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    // Add event listener
    if (toggle) {
        toggle.addEventListener('change', function () {
            if (this.checked) {
                localStorage.setItem('theme', 'dark');
                applyTheme('dark');
            } else {
                localStorage.setItem('theme', 'light');
                applyTheme('light');
            }
        });
    }
});