<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">
  <div class="container">
    <h1 class="mb-4">➕ Tambah Barang</h1>

    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('items.store') }}" method="POST">
      @csrf

      {{-- GEDUNG --}}
      <div class="mb-3">
        <label class="form-label">Gedung</label>
        <select id="buildingSelect" class="form-select" required>
          <option value="">-- Pilih Gedung --</option>
          @foreach ($buildings as $building)
            <option value="{{ $building->id }}">{{ $building->name }}</option>
          @endforeach
        </select>
      </div>

      {{-- RUANGAN --}}
      <div class="mb-3">
        <label class="form-label">Ruangan</label>
        <select name="room_id" id="roomSelect" class="form-select" required>
          <option value="">-- Pilih Ruangan --</option>
        </select>
      </div>

      {{-- KATEGORI --}}
      <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      {{-- 2 kolom: Kode Barang & Nama Barang --}}
       <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Router WiFi AC1200" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" class="form-control" placeholder="Contoh: BRG001" required>
            </div>
        </div>

      {{-- 2 kolom: Merk --}}
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Satuan</label>
          <select name="satuan" class="form-select" required>
            <option value="">Pilih Satuan</option>
            <option value="Unit">Unit</option>
            <option value="Pcs">Pcs</option>
            <option value="Box">Box</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Merk</label>
          <input type="text" name="merk" class="form-control" placeholder="Contoh: TP-Link" required>
        </div>
      </div>

      {{-- STOK --}}
      <div class="mb-3">
        <label class="form-label">Stok Awal</label>
        <input type="number" name="quantity" class="form-control" min="1" placeholder="Masukkan stok" required>
      </div>

      <button type="submit" class="btn btn-success">💾 Simpan</button>
      <a href="{{ route('items.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
  </div>

  <script>
    // Load ruangan sesuai gedung
    $('#buildingSelect').on('change', function () {
      const buildingId = $(this).val();
      const roomSelect = $('#roomSelect');
      roomSelect.html('<option value="">Memuat...</option>');

      if (buildingId) {
        $.ajax({
          url: '/get-rooms/' + buildingId,
          type: 'GET',
          success: function (rooms) {
            let options = '<option value="">-- Pilih Ruangan --</option>';
            rooms.forEach(room => {
              options += `<option value="${room.id}">${room.name}</option>`;
            });
            roomSelect.html(options);
          },
          error: function () {
            roomSelect.html('<option value="">Gagal memuat ruangan</option>');
          }
        });
      } else {
        roomSelect.html('<option value="">-- Pilih Ruangan --</option>');
      }
    });
  </script>
</body>
</html>
