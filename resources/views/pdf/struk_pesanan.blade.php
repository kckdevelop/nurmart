<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pemesanan - {{ $pesanan->no_pesanan }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 16px 22px 20px 22px; /* Margin tepi: atas 16px, kanan 22px, bawah 20px, kiri 22px */
            font-family: 'Courier New', Courier, monospace;
            font-size: 8px;
            line-height: 1.35;
            color: #000;
            background: #fff;
        }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-left   { text-align: left; }
        .bold        { font-weight: bold; }

        /* Store Header */
        .store-header {
            text-align: center;
            margin-bottom: 5px;
        }
        .store-title {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .store-address {
            font-size: 7.5px;
            line-height: 1.3;
            color: #222;
        }
        .doc-badge {
            display: inline-block;
            margin-top: 3px;
            padding: 2px 6px;
            border: 1px solid #000;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Dividers */
        .divider {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .double-divider {
            border: none;
            border-top: 1.5px solid #000;
            margin: 5px 0;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 1.5px 0;
            vertical-align: top;
            font-size: 8px;
        }

        /* Metadata Transaksi Nota */
        .tbl-meta td.label {
            width: 35%;
            text-align: left;
            color: #333;
        }
        .tbl-meta td.value {
            width: 65%;
            text-align: right;
            word-break: break-all;
        }

        /* Item Barang */
        .item-name {
            font-size: 8px;
            font-weight: bold;
            padding-top: 2px;
        }
        .tbl-item td.qty {
            width: 48%;
            text-align: left;
            color: #444;
            font-size: 7.5px;
        }
        .tbl-item td.subtotal {
            width: 52%;
            text-align: right;
            font-weight: bold;
        }

        /* Total */
        .tbl-total td.label {
            width: 46%;
            text-align: left;
        }
        .tbl-total td.value {
            width: 54%;
            text-align: right;
        }
        .total-row td {
            font-size: 9px;
            font-weight: bold;
            padding: 2px 0;
        }

        /* Customer Box */
        .customer-info {
            font-size: 7.5px;
            line-height: 1.35;
            margin: 4px 0;
            padding: 3px 0;
        }

        /* Footer */
        .footer-note {
            font-size: 7px;
            text-align: center;
            line-height: 1.35;
            margin-top: 6px;
            color: #222;
        }
    </style>
</head>
<body>
    <!-- 1. Header Toko -->
    <div class="store-header">
        <div class="store-title">{{ $pengaturan->nama_toko ?? 'TOKO KELONTONG NURMART' }}</div>
        <div class="store-address">
            @if(!empty($pengaturan->slogan))
                {{ $pengaturan->slogan }}<br>
            @endif
            @if(!empty($pengaturan->alamat))
                {{ $pengaturan->alamat }}<br>
            @endif
            @if(!empty($pengaturan->no_telepon))
                Telp / WA: {{ $pengaturan->no_telepon }}
            @endif
        </div>
        <div class="doc-badge">STRUK PESANAN ONLINE</div>
    </div>

    <hr class="divider">

    <!-- 2. Metadata Pesanan -->
    <table class="tbl-meta">
        <tr>
            <td class="label">No. Pesanan</td>
            <td class="value bold">{{ $pesanan->no_pesanan }}</td>
        </tr>
        <tr>
            <td class="label">Waktu Pesan</td>
            <td class="value">
                {{ \Carbon\Carbon::parse($pesanan->tanggal ?? $pesanan->created_at)->format('d/m/Y H:i') }}
            </td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value bold" style="text-transform: uppercase;">{{ $pesanan->status }}</td>
        </tr>
    </table>

    <hr class="divider">

    <!-- 3. Informasi Pemesan & Pengiriman -->
    <div class="customer-info">
        <div><strong>Pemesan:</strong> {{ $pesanan->nama_pemesan }}</div>
        @if(!empty($pesanan->no_telepon))
            <div><strong>No. HP/WA:</strong> {{ $pesanan->no_telepon }}</div>
        @endif
        @if(!empty($pesanan->alamat))
            <div><strong>Alamat:</strong> {{ $pesanan->alamat }}</div>
        @endif
        @if(!empty($pesanan->catatan))
            <div><strong>Catatan:</strong> <em>{{ $pesanan->catatan }}</em></div>
        @endif
    </div>

    <hr class="divider">

    <!-- 4. Rincian Item Produk -->
    <table class="tbl-item">
        @foreach($pesanan->details as $item)
            <tr>
                <td colspan="2" class="item-name">{{ $item->barang->nama_barang ?? 'Barang' }}</td>
            </tr>
            <tr>
                <td class="qty">
                    {{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                </td>
                <td class="subtotal">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </table>

    <hr class="divider">

    <!-- 5. Total Belanja -->
    <table class="tbl-total">
        <tr class="total-row">
            <td class="label">TOTAL BELANJA</td>
            <td class="value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <hr class="double-divider">

    <!-- 6. Footer & Catatan Toko -->
    <div class="footer-note">
        *** TERIMA KASIH TELAH MEMESAN ***<br>
        @if(!empty($pengaturan->footer_struk))
            {!! nl2br(e($pengaturan->footer_struk)) !!}<br>
        @else
            Simpan struk ini sebagai bukti pemesanan yang sah.<br>
        @endif
        @if(!empty($pengaturan->no_telepon))
            Konfirmasi Pesanan: {{ $pengaturan->no_telepon }}
        @endif
    </div>
</body>
</html>
