const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 3000,
    delay: 400,
    // reset: true // Animation repeat
});

sr.reveal('.main');
sr.reveal('.logo', { origin: 'left' });