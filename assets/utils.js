'use strict'

const acceptButton = document.querySelector('#marketing-banner a');
const refusedButton = document.querySelector('#marketing-banner button');
const banner = document.getElementById('marketing-banner');

acceptButton.addEventListener('click', (event) => {
    event.preventDefault();

    localStorage.setItem('cookiesAccepted', 'true');
    banner.style.display = 'none';
});

refusedButton.addEventListener('click', (event) => {
    event.preventDefault();

    localStorage.setItem('cookiesAccepted', 'false');
    banner.style.display = 'none';
});

window.addEventListener('load', () => {
    const cookiesAccepted = localStorage.getItem('cookiesAccepted');
    if (cookiesAccepted === 'true' || cookiesAccepted === 'false') {
        banner.style.display = 'none';
    }
});

console.log('This log comes from assets/utils.js')
