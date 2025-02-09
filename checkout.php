<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content=
  "width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link rel="stylesheet" href="style.css">
  <script type="text/javascript" src=
  "https://app.midtrans.com/snap/snap.js" data-client-key=
  "Mid-client-xxxxxxxxx"></script>
  <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .checkout-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .product-card, .form-card {
            margin-top: 20px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
  </style>
</head>
<body>
  <div class="checkout-container">
    <h1>KONFIRMASI PEMBAYARAN</h1>
    <div class="product-card">
      <h2>TOTAL PRODUCT :</h2>
      <p><strong>Nama File :</strong> Produk tidak tersedia</p>
      <p><strong>Harga :</strong> Rp 0</p>
    </div>
    <div class="form-card">
      <form id="payment-form" method="post" action=
      "payment_process.php" name="payment-form">
        <div class="form-group">
          <label for="email">Masukan Email:</label> <input type=
          "email" id="email" name="email" required="">
        </div><input type="hidden" name="nama_produk" value=
        "Produk tidak tersedia"> <input type="hidden" name=
        "harga_produk" value="0"> <button class="btn" id=
        "pay-button" type="button">Bayar Sekarang</button>
      </form>
    </div>
  </div>
  <script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        var email = document.getElementById('email').value;
        var nama_produk = 'Produk tidak tersedia';
        var harga_produk = '0';

        fetch('payment_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'email': email,
                'nama_produk': nama_produk,
                'harga_produk': harga_produk
            }).toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.snapToken) {
                window.snap.pay(data.snapToken, {
                    onSuccess: function(result) {
                        console.log(result);
                        alert("Pembayaran berhasil!");
                        
                        // Simpan data ke session
                        fetch('payment_process.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                'email': email,
                                'nama_produk': nama_produk,
                                'harga_produk': harga_produk,
                                'order_id': result.order_id,
                                'tanggal_pembelian': new Date().toISOString()
                            }).toString()
                        });

                        // Kirim detail ke bot Telegram
                        var message = encodeURIComponent(`Pembayaran berhasil! Details:\nEmail: ${email}\nProduk: ${nama_produk}\nHarga: ${harga_produk}\nOrder ID: ${result.order_id}\nTanggal: ${new Date().toLocaleString()}`);
                        fetch(`https://api.telegram.org/bot7379394051:AAEnOcwrazqCOaouKDJlhKYq_EwBPPqVPSE/sendMessage?chat_id=-4584589529&text=${message}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Pesan berhasil dikirim ke Telegram:', data);
                                window.location.href = 'payment_status.php';
                            })
                            .catch(error => {
                                console.error('Gagal mengirim pesan ke Telegram:', error);
                                alert("Gagal mengirim detail pembayaran");
                            });
                    },
                    onPending: function(result) {
                        console.log(result);
                        alert("Menunggu pembayaran Anda!");
                    },
                    onError: function(result) {
                        console.log(result);
                        alert("Pembayaran gagal!");
                    },
                    onClose: function() {
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                    }
                });
            } else {
                alert('Token pembayaran tidak tersedia.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
  </script>
</body>
</html>