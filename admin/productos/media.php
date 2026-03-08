<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';

$producto_id = (int)($_GET['id'] ?? 0);
// Verificar que el producto exista
$check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
$check->execute([$producto_id]);
if (!$check->fetch()) {
    die('El producto no existe');
}

// Media existente
$stmt = $pdo->prepare("
    SELECT * FROM productos_media
    WHERE producto_id = ?
    ORDER BY orden, id
");
$stmt->execute([$producto_id]);
$media = $stmt->fetchAll();
?>

<h2>Galería del producto</h2>

<style>
.dropzone{
  border:2px dashed #ccc;
  padding:30px;
  text-align:center;
  border-radius:10px;
  background:#fafafa;
  cursor:pointer;
}
.dropzone.dragover{
  background:#eee;
}
.preview{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(140px,1fr));
  gap:12px;
  margin-top:20px;
}
.preview-item{
  position:relative;
  border:1px solid #ddd;
  border-radius:8px;
  overflow:hidden;
}
.preview-item img,
.preview-item video{
  width:100%;
  height:120px;
  object-fit:cover;
}
.preview-item a{
  position:absolute;
  top:6px;
  right:6px;
  background:#c0392b;
  color:#fff;
  padding:4px 8px;
  border-radius:6px;
  font-size:12px;
}
</style>

<div class="dropzone" id="dropzone">
  Arrastrá imágenes o videos acá<br>
  <small>Imágenes → .webp automático / Videos MP4 máx 20MB</small>
</div>

<input type="file" id="fileInput" multiple hidden>

<div class="preview" id="preview">
<?php foreach ($media as $m): ?>
  <div class="preview-item">
    <?php if ($m['tipo'] === 'imagen'): ?>
      <img src="/uploads/productos/<?= $producto_id ?>/<?= $m['archivo'] ?>">
    <?php else: ?>
      <video src="/uploads/productos/<?= $producto_id ?>/<?= $m['archivo'] ?>" muted></video>
    <?php endif; ?>
    <a href="eliminar_media.php?id=<?= $m['id'] ?>&producto=<?= $producto_id ?>"
       onclick="return confirm('¿Eliminar media?')">✕</a>
  </div>
<?php endforeach; ?>
</div>

<form id="uploadForm" method="post" enctype="multipart/form-data"
      action="upload_media.php">
  <input type="hidden" name="producto_id" value="<?= $producto_id ?>">
  <input type="file" name="media[]" id="mediaFiles" multiple hidden>
</form>

<script>
const dz = document.getElementById('dropzone');
const input = document.getElementById('mediaFiles');
const form = document.getElementById('uploadForm');

dz.addEventListener('click', () => input.click());

dz.addEventListener('dragover', e => {
  e.preventDefault();
  dz.classList.add('dragover');
});

dz.addEventListener('dragleave', () => dz.classList.remove('dragover'));

dz.addEventListener('drop', e => {
  e.preventDefault();
  dz.classList.remove('dragover');
  input.files = e.dataTransfer.files;
  form.submit();
});

input.addEventListener('change', () => form.submit());
</script>
