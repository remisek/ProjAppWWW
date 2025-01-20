function gettheDate() {
    const Todays = new Date();
    const TheDate = `${Todays.getMonth() + 1}/${Todays.getDate()}/${Todays.getFullYear()}`;
    document.getElementById("data").innerHTML = TheDate;
}

let timerID = null;
let timerRunning = false;

function stopclock() {
    if (timerRunning) {
        clearTimeout(timerID);
    }
    timerRunning = false;
}

function startclock() {
    stopclock();
    gettheDate();
    showtime();
}

function showtime() {
    const now = new Date();
    let hours = now.getHours();
    const minutes = now.getMinutes();
    const seconds = now.getSeconds();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    const timeValue = `${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds} ${ampm}`;
    document.getElementById("zegarek").innerHTML = timeValue;
    timerID = setTimeout(showtime, 1000);
    timerRunning = true;
}

// New functions to control the clock
function pauseClock() {
    stopclock();
}

function resumeClock() {
    if (!timerRunning) {
        startclock();
    }
}

function resetClock() {
    stopclock();
    document.getElementById("zegarek").innerHTML = "00:00:00 AM";
    document.getElementById("data").innerHTML = "";
    gettheDate();
}
