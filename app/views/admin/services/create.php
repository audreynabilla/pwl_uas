<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">

    
    <link href="<?= baseUrl('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>

    <main class="admin-main min-vh-100 p-4 p-lg-5">
        <div class="container" style="max-width:860px">

            
            
            
            <a class="btn btn-outline-paw mb-3" href="index.php?page=admin&section=services">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            
            
            
            <form 
                class="form-card p-4" 
                method="post" 
                enctype="multipart/form-data" 
                action="index.php?page=admin&section=services&action=create"
            >

                <?= csrfField() ?>

                <h1 class="section-title mb-4">Tambah Layanan</h1>

                <div class="row g-3">

                    
                    
                    

                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Layanan</label>
                        <input 
                            name="name" 
                            class="form-control" 
                            required
                        >
                    </div>

                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category" class="form-select" required>
                            <option>Kucing</option>
                            <option>Anjing</option>
                        </select>
                    </div>

                    
                    
                    

                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Harga</label>
                        <input 
                            type="number" 
                            min="0" 
                            name="price" 
                            class="form-control" 
                            required
                        >
                    </div>

                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Durasi/menit</label>
                        <input 
                            type="number" 
                            min="1" 
                            name="duration" 
                            class="form-control" 
                            required
                        >
                    </div>

                    
                    
                    

                    
                    <div class="col-12">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea 
                            name="description" 
                            class="form-control" 
                            rows="4" 
                            required
                        ></textarea>
                    </div>

                    
                    <div class="col-12">
                        <label class="form-label fw-bold">Upload Gambar</label>
                        
                        <input 
                            type="file" 
                            name="image" 
                            class="form-control image-input" 
                            accept=".jpg,.jpeg,.png" 
                            data-preview="#imgPreview" 
                            required
                        >
                        
                        <img 
                            id="imgPreview" 
                            class="table-thumb d-none mt-3" 
                            alt="Preview"
                        >
                    </div>

                </div> 

                
                
                
                <button class="btn btn-primary-paw mt-4">
                    <i class="bi bi-save me-2"></i>Simpan
                </button>

            </form>
            
            
            

        </div> 
    </main>

    
    <script src="<?= baseUrl('assets/js/main.js') ?>"></script>

</body>
</html>