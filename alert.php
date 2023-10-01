<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko"/>
    <meta name="author" content="Miha Šafranko" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Game Upload</title>
    <style>
        * {
            font-family: "Poppins", sans-serif;
        }
        .toast-container {
            position: fixed;
            bottom: 25px;
            right: 30px;
            display: flex;
            flex-direction: column-reverse;
            align-items: flex-end;
        }

        .toast {
            border-radius: 12px;
            background: #fff;
            padding: 20px 35px 20px 25px;
            box-shadow: 0 6px 20px -5px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(100%);
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.35);
            margin-top: 10px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .toast.active {
            opacity: 1;
            transform: translateY(0%);
        }

        .toast .toast-content {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .toast-content .check {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 35px;
            min-width: 35px;
            background-color: #4070f4;
            color: #fff;
            font-size: 20px;
            border-radius: 50%;
        }

        .toast-content .message {
            display: flex;
            flex-direction: column;
            margin: 0 20px;
        }

        .message .text {
            font-size: 16px;
            font-weight: 400;
            color: #666666;
        }

        .message .text.text-1 {
            font-weight: 600;
            color: #333;
        }

        .toast .close {
            position: absolute;
            top: 10px;
            right: 15px;
            padding: 5px;
            cursor: pointer;
            opacity: 0.7;
        }

        .toast .close:hover {
            opacity: 1;
        }

        .toast .progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 0;
            background-color: #4070f4;
            transition: width 5s linear;
        }

        .toast.active .progress {
            width: 100%;
        }

        .toast.active ~ button {
            pointer-events: none;
            overflow-y: scroll;
        }

        .body-overflow-hidden {
            overflow: hidden;
        }
    </style>
</head>
<body>
<div class="toast-container">
</div>
<script>
    let timerInterval;
    let startTime;
    let duration = 5000; // 5 seconds duration

    function checkAndShowToast() {
        const prijavaCookie = getCookie("prijava");
        const goodCookie = getCookie("good");
        const warningCookie = getCookie("warning");
        const errorCookie = getCookie("error");
        const decodedPrijavaCookie = decodeURIComponent(prijavaCookie);

        if (prijavaCookie) {
            if (goodCookie === "1") {
                showToast("Success", decodedPrijavaCookie, "success");
            } else if (warningCookie === "1") {
                showToast("Warning", decodedPrijavaCookie, "warning");
            } else if (errorCookie === "1") {
                showToast("Error", decodedPrijavaCookie, "error");
            }
        }
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) {
            return parts.pop().split(';').shift();
        }
        return null;
    }

    function showToast(title, message, type) {
        const toastContainer = document.querySelector(".toast-container");

        const toast = document.createElement("div");
        toast.classList.add("toast");

        const toastContent = document.createElement("div");
        toastContent.classList.add("toast-content");

        const check = document.createElement("div");
        check.classList.add("check");

        switch (type) {
            case "success":
                check.innerHTML = '<i class="fas fa-check"></i>';
                check.style.backgroundColor = "#4070f4";
                break;
            case "warning":
                check.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                check.style.backgroundColor = "#ffc107";
                break;
            case "error":
                check.innerHTML = '<i class="fas fa-times"></i>';
                check.style.backgroundColor = "#f44336";
                break;
            default:
                check.innerHTML = '<i class="fas fa-check"></i>';
                check.style.backgroundColor = "#4070f4";
        }

        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message");

        const text1 = document.createElement("span");
        text1.classList.add("text", "text-1");
        text1.textContent = title;

        const text2 = document.createElement("span");
        text2.classList.add("text", "text-2");
        text2.textContent = message;

        messageDiv.appendChild(text1);
        messageDiv.appendChild(text2);

        toastContent.appendChild(check);
        toastContent.appendChild(messageDiv);
        toast.appendChild(toastContent);

        const progressBar = document.createElement("div");
        progressBar.classList.add("progress");
        toast.appendChild(progressBar);

        const closeButton = document.createElement("i");
        closeButton.classList.add("fas", "fa-times", "close");
        closeButton.addEventListener("click", () => {
            clearInterval(timerInterval);
            toastContainer.removeChild(toast);
            document.body.classList.remove("body-overflow-hidden");
        });

        toast.appendChild(closeButton);

        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.classList.add("active");
            startTime = Date.now();
            timerInterval = setInterval(updateProgressBar, 10); // Update more frequently
        }, 100);

        setTimeout(() => {
            toast.classList.remove("active");
            setTimeout(() => {
                toastContainer.removeChild(toast);
            }, 500);
        }, duration);
    }

    function updateProgressBar() {
        const currentTime = Date.now() - pausedTime; // Consider time elapsed during pause
        const elapsedPercentage = Math.min((currentTime - startTime) / duration * 100, 100);
        const progressBar = document.querySelector(".toast.active .progress");
        progressBar.style.width = `${elapsedPercentage}%`;

        if (elapsedPercentage >= 100) {
            clearInterval(timerInterval);
        }
    }

    checkAndShowToast();

    document.cookie = 'prijava=; Max-Age=0';
    document.cookie = 'error=; Max-Age=0';
    document.cookie = 'warning=; Max-Age=0';
    document.cookie = 'good=; Max-Age=0';
</script>
<script>
    // Place this script at the end of your HTML body
    const progressBarInterval = 30; // Update the progress bar every 30 milliseconds

    function startProgressBar() {
        const toast = document.querySelector(".toast.active");
        if (toast) {
            startTime = Date.now();
            clearInterval(timerInterval);
            timerInterval = setInterval(updateProgressBar, progressBarInterval);
        }
    }

    function stopProgressBar() {
        clearInterval(timerInterval);
    }

    document.addEventListener("DOMContentLoaded", startProgressBar);
    document.addEventListener("visibilitychange", function() {
        if (document.hidden) {
            stopProgressBar();
        } else {
            startProgressBar();
        }
    });
</script>
<script>
    // Place this script at the end of your HTML body
    const progressBarInterval = 10; // Update the progress bar every 10 milliseconds

    function startProgressBar() {
        const toast = document.querySelector(".toast.active");
        if (toast) {
            startTime = Date.now();
            clearInterval(timerInterval);
            timerInterval = setInterval(updateProgressBar, progressBarInterval);
        }
    }

    function stopProgressBar() {
        clearInterval(timerInterval);
        pausedTime += Date.now() - startTime;
    }

    document.addEventListener("DOMContentLoaded", startProgressBar);
    document.addEventListener("visibilitychange", function() {
        if (document.hidden) {
            stopProgressBar();
        } else {
            startProgressBar();
        }
    });
</script>
</body>
</html>
