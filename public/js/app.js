import TetherToast from "/js/Components/toast.js";
import {copyToClipboard} from "/js/utils/copyToClipboard.js";

let activeToasts = [];

let preTags = document.getElementsByTagName('pre')

for(let i = 0; i < preTags.length; i++) {
    preTags[i].addEventListener('click', function() {
        handlePreClick(preTags[i])
    });
}

function handlePreClick(element) {
    const content = element.innerText;
    activeToasts.forEach((toast) => {
        toast.hide();
    })

    copyToClipboard(content);

    activeToasts.push(new TetherToast({
        message: 'Copied to clipboard!',
        duration: 3000
    }));
}

document.querySelector('.theme-toggle').addEventListener('click', function() {
    document.body.classList.toggle('dark');
})