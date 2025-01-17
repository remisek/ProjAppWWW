var computed = false;
var decimal = 0;

function convert(entryform, from, to) {
    convertfrom = from.selectedIndex;
    convertto = to.selectedIndex;
    entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);
}

function addChar(input, character) {
    if ((character == "." && decimal == "0") || character != ".") {
        (input.value == "" || input.value == "0") ? input.value = character : input.value += character;
        convert(input.form, input.form.measure1, input.form.measure2);
        computed = true;
        if (character == ".") {
            decimal = 1;
        }
    }
}

function openVothcom() {
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no");
}

function clear(form) {
    form.input.value = 0;
    form.display.value = 0;
    decimal = 0;
}

// Function to change background color
function changeBackground(hexNumber) {
    document.body.style.backgroundColor = hexNumber; // Use body.style.backgroundColor instead of document.bgColor
}

// Function to generate a random color
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Function to change to a random background color
function changeRandomColor() {
    const randomColor = getRandomColor();
    changeBackground(randomColor);
}

function setGreeting() {
    const now = new Date();
    const hours = now.getHours();
    let greeting;

    if (hours < 12) {
        greeting = "Dzień dobry";
    } else if (hours < 18) {
        greeting = "Dobry wieczór";
    } else {
        greeting = "Dobranoc";
    }

    document.getElementById("greeting").innerText = greeting;
}

// Start clock and set greeting on page load
window.onload = function() {
    startclock();
    setGreeting();
}
