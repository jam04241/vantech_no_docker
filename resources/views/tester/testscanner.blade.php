<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR / Barcode Test</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        #reader {
            width: 350px;
            margin: 20px auto;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center">Camera Scanner Test</h2>
    <div id="reader"></div>
    <pre id="result" style="text-align:center"></pre>

    <script>
        const html5QrCode = new Html5Qrcode("reader");
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                html5QrCode.start(
                    cameraId,
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText) => {
                        document.getElementById("result").textContent = "✅ " + decodedText;
                    },
                    (error) => {
                        console.warn(error);
                    }
                );
            } else {
                alert("No cameras found");
            }
        }).catch(err => {
            alert("Camera access failed: " + err);
            console.error(err);
        });
    </script>
</body>

</html>