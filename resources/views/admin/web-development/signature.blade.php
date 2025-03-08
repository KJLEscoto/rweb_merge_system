<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature Pad</title>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .signature-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        canvas {
            border: 2px solid #000;
            background-color: #fff;
        }

        .btn-group {
            margin-top: 10px;
        }

        button {
            padding: 10px 15px;
            margin-right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="signature-container">
    <canvas id="signature-pad" width="400" height="200"></canvas>
    
    <div class="btn-group">
        <button id="clear">Clear</button>
        <button id="save">Save Signature</button>
    </div>
</div>

<script>
    var canvas = document.getElementById("signature-pad");
    var signaturePad = new SignaturePad(canvas);

    document.getElementById("clear").addEventListener("click", function () {
        signaturePad.clear();
    });

    document.getElementById("save").addEventListener("click", function () {
        if (signaturePad.isEmpty()) {
            alert("Please provide a signature first.");
            return;
        }

        var dataURL = signaturePad.toDataURL("image/png");

        $.ajax({
            url: "{{ route('signature.store') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                signature: dataURL
            },
            success: function (response) {
                alert("Signature saved successfully!");
                window.location.reload();
            },
            error: function () {
                alert("Failed to save signature.");
            }
        });
    });
</script>

</body>
</html>
