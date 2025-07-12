document.addEventListener("DOMContentLoaded", function() {
    const container = document.querySelector('.container');
    container.style.opacity = 0;
    container.style.transform = 'translateY(20px)';
    setTimeout(() => {
        container.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        container.style.opacity = 1;
        container.style.transform = 'translateY(0)';
    }, 100);
});