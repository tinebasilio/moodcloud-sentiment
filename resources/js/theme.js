import gsap from 'gsap';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light.css';
import 'tippy.js/themes/dark.css';

// Theme Configuration
const themeConfig = {
    light: {
        primary: '#6366f1',
        secondary: '#8b5cf6',
        accent: '#f59e0b',
        neutral: '#3d4451',
        'base-100': '#ffffff',
        'base-200': '#f3f4f6',
        'base-300': '#e5e7eb',
    },
    dark: {
        primary: '#818cf8',
        secondary: '#a78bfa',
        accent: '#fbbf24',
        neutral: '#191d24',
        'base-100': '#1f2937',
        'base-200': '#111827',
        'base-300': '#0f172a',
    }
};

// Theme Animations
const animations = {
    fadeIn: {
        opacity: 0,
        y: 20,
        duration: 0.5,
        ease: 'power2.out'
    },
    slideIn: {
        x: -30,
        opacity: 0,
        duration: 0.5,
        ease: 'power2.out'
    },
    scaleIn: {
        scale: 0.95,
        opacity: 0,
        duration: 0.5,
        ease: 'back.out(1.7)'
    }
};

// Theme Initialization
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    // Set initial checkbox state
    const themeToggle = document.querySelector('.theme-controller');
    if (themeToggle) {
        themeToggle.checked = savedTheme === 'light';
    }
    
    // Animate content on load
    gsap.from('.stats', animations.fadeIn);
    gsap.from('.card', {
        ...animations.scaleIn,
        stagger: 0.1
    });
    
    // Initialize tooltips
    tippy('[data-tippy-content]', {
        theme: savedTheme === 'dark' ? 'dark' : 'light'
    });
}

// Theme Toggle Handler
function toggleTheme(event) {
    const newTheme = event.target.checked ? 'light' : 'dark';
    
    // Animate theme transition
    gsap.to('body', {
        opacity: 0,
        duration: 0.2,
        onComplete: () => {
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update tooltip theme
            tippy.setDefaultProps({
                theme: newTheme === 'dark' ? 'dark' : 'light'
            });
            
            // Update chart theme
            if (window.currentChart) {
                window.currentChart.update({
                    options: {
                        ...chartTheme[newTheme]
                    }
                });
            }
            
            gsap.to('body', {
                opacity: 1,
                duration: 0.2
            });
        }
    });

    // Send theme preference to server
    fetch('/set-theme', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ theme: newTheme })
    });
}

// Chart Themes
const chartTheme = {
    light: {
        background: '#ffffff',
        textColor: '#374151',
        gridColor: '#e5e7eb',
        tooltipTheme: 'light'
    },
    dark: {
        background: '#1f2937',
        textColor: '#e5e7eb',
        gridColor: '#374151',
        tooltipTheme: 'dark'
    }
};

// Initialize theme when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    
    // Add event listener to theme toggle
    const themeToggle = document.querySelector('.theme-controller');
    if (themeToggle) {
        themeToggle.addEventListener('change', toggleTheme);
    }
});

// Export configuration
export {
    themeConfig,
    animations,
    initTheme,
    toggleTheme,
    chartTheme
};