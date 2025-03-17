import { Html5QrcodeScanner } from "html5-qrcode";

document.addEventListener("DOMContentLoaded", function () {
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Scanned: ${decodedText}`);
        alert(`Scanned: ${decodedText}`);
    }

    let scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    scanner.render(onScanSuccess);
});
