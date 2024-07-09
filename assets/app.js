import './styles/app.css';
import './utils';
import './lordicon';

// Scroll event listener to add shadow to header
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    window.scrollY > 0 ? header.classList.add('shadow-md') : header.classList.remove('shadow-md');
});

console.log('This log comes from assets/app.js');
