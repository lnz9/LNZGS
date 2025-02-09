document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('header');
    const hamburger = document.getElementById('hamburger');
    const navbar = document.getElementById('navbar');
    const typingTexts = [
        document.getElementById('typing-text-1'),
        document.getElementById('typing-text-2')
    ];

    let currentIndex = 0;
    let typingIndex = 0;
    let speed = 50; // Default speed of typing in milliseconds
    let typingCompleted = false; // Flag to check if typing is completed

    // Initialize the typing effect
    function type() {
        if (!typingCompleted && currentIndex < typingTexts.length) {
            let textElement = typingTexts[currentIndex];
            let text = textElement.getAttribute('data-text'); // Get text from data attribute

            if (typingIndex < text.length) {
                textElement.innerHTML += text.charAt(typingIndex);
                typingIndex++;
                setTimeout(type, speed);
            } else {
                currentIndex++;
                typingIndex = 0;
                
                if (currentIndex < typingTexts.length) {
                    // Clear the current paragraph and start typing in the next paragraph
                    textElement.innerHTML = '';
                    text = typingTexts[currentIndex].getAttribute('data-text');
                }
                setTimeout(type, speed);
            }
        } else {
            typingCompleted = true; // Mark typing as completed
        }
    }

    // Set data-text attributes for typing effect
    typingTexts.forEach(textElement => {
        textElement.setAttribute('data-text', textElement.textContent);
        textElement.textContent = ''; // Clear text content initially
    });

    type();

    // Adjust typing speed based on screen orientation
    function adjustTypingSpeed() {
        if (window.innerWidth < window.innerHeight) {
            speed = 40; // Faster typing speed in portrait mode
        } else {
            speed = 60; // Slower typing speed in landscape mode
        }
    }

    // Prevent typing speed adjustment after scroll
    window.addEventListener('scroll', () => {
        if (!typingCompleted) {
            typingCompleted = true; // Mark typing as completed to avoid speed changes
        }
        if (window.scrollY > 50) {
            header.classList.add('header-scrolled');
            hamburger.style.display = 'none';
        } else {
            header.classList.remove('header-scrolled');
            hamburger.style.display = 'block';
        }
    });
    
    // Hamburger menu toggle
    hamburger.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });

    // Update typing speed on window resize (if necessary)
    window.addEventListener('resize', () => {
        adjustTypingSpeed();
    });

    // Popup functionality
    const popupOverlay = document.getElementById('popup-overlay');
    const popup = document.getElementById('popup-modal');
    const popupClose = document.getElementById('popup-close');
    const popupCloseBtn = document.getElementById('popup-close-btn');
    const popupJoin = document.getElementById('popup-join');

    function showPopup() {
        if (popup && popupOverlay) {
            popupOverlay.style.display = 'flex'; // Ensure popup is visible
        }
    }

    function closePopup() {
        if (popup && popupOverlay) {
            popupOverlay.style.display = 'none'; // Hide popup and overlay
        }
    }

    // Attach event listeners
    if (popupClose) popupClose.addEventListener('click', closePopup);
    if (popupCloseBtn) popupCloseBtn.addEventListener('click', closePopup);
    if (popupJoin) popupJoin.addEventListener('click', () => {
        window.location.href = 'https://whatsapp.com/channel/0029VaNn8j37YSczUJUGp80Q'; // Update this URL
    });

    // Optionally, show popup after some time
    setTimeout(showPopup, 5000);
});