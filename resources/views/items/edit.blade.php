<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">
  <div class="container">
    <h1 class="mb-4">✏️ Edit Barang</h1>

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

    <form action="{{ route('items.update', $item->id) }}" method="POST">
      @csrf
      @method('PUT')

      {{-- GEDUNG --}}
      <div class="mb-3">
        <label class="form-label">Gedung</label>
        <select id="buildingSelect" class="form-select" required>
          <option value="">-- Pilih Gedung --</option>
          @foreach ($buildings as $building)
            <option value="{{ $building->id }}"
              {{ $item->room->building_id == $building->id ? 'selected' : '' }}>
              {{ $building->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- RUANGAN --}}
      <div class="mb-3">
        <label class="form-label">Ruangan</label>
        <select name="room_id" id="roomSelect" class="form-select" required>
          <option value="{{ $item->room->id }}">{{ $item->room->name }}</option>
        </select>
      </div>

      {{-- KATEGORI --}}
      <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}"
              {{ $item->category_id == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- 2 kolom: Nama Barang & Kode Barang --}}
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nama Barang</label>
          <input type="text" name="name" class="form-control"
                 value="{{ $item->name }}" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Kode Barang</label>
          <input type="text" name="code" class="form-control"
            value="{{ $item->code }}" required>
        </div>
      </div>

      {{-- 2 kolom: Satuan & Merk --}}
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Satuan</label>
          <select name="satuan" class="form-select" required data-bs-display="static" >
            <option value="Unit" {{ $item->satuan == 'Unit' ? 'selected' : '' }}>Unit</option>
            <option value="Pcs" {{ $item->satuan == 'Pcs' ? 'selected' : '' }}>Pcs</option>
            <option value="Box" {{ $item->satuan == 'Box' ? 'selected' : '' }}>Box</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Merk</label>
          <input type="text" name="merk" class="form-control"
                 value="{{ $item->merk }}" required>
        </div>
      </div>

      {{-- STOK --}}
      <div class="mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="quantity" class="form-control"
               value="{{ $item->quantity }}" min="1" required>
      </div>

      <button type="submit" class="btn btn-primary">✅ Update</button>
      <a href="{{ route('items.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
  </div>


<script>
  // AJAX ruangan berdasarkan gedung
  $('#buildingSelect').on('change', function () {
      const buildingId = $(this).val();
      const roomSelect = $('#roomSelect');

      roomSelect.html('<option>Memuat...</option>');

      $.get('/get-rooms/' + buildingId, function (rooms) {
          let options = "";
          rooms.forEach(room => {
              options += `<option value="${room.id}">${room.name}</option>`;
          });
          roomSelect.html(options);
      });
  });
</script>

</body>
</html>
