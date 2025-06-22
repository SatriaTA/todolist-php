<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi To-Do List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .task-item {
            transition: all 0.3s ease;
        }
        .task-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .completed {
            text-decoration: line-through;
            opacity: 0.7;
        }
        .status-badge {
            font-size: 0.8rem;
        }
        .checkbox-custom {
            transform: scale(1.2);
            cursor: pointer;
        }
        .btn-action {
            margin: 0 2px;
        }
        /* Fixed footer styling */
        body {
            padding-bottom: 80px; /* Add padding to prevent content from being hidden behind fixed footer */
        }
        .footer-fixed {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-light">
    <?php
    /**
     * APLIKASI TO-DO LIST
     * 
     * Fitur:
     * - Tambah tugas baru
     * - Edit judul tugas
     * - Toggle status tugas (checkbox)
     * - Hapus tugas
     * - Statistik tugas
     * 
     * Best Practice:
     * - Fungsi terpisah untuk setiap operasi
     * - Validasi input
     * - Komentar yang jelas
     * - Penamaan variabel yang deskriptif
     */

    // Start session untuk menyimpan data
    session_start();

    // ========================================
    // INISIALISASI DATA
    // ========================================
    
    /**
     * Array tugas dengan struktur object
     * Setiap tugas memiliki: id, title, status
     */
    if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = [];
    }
    $tasks = &$_SESSION['tasks'];

    // ========================================
    // FUNGSI UTILITAS
    // ========================================
    
    /**
     * Mendapatkan ID unik berikutnya untuk tugas baru
     * 
     * @param array $tasks Array tugas yang ada
     * @return int ID unik berikutnya
     */
    function getNextId($tasks) {
        if (empty($tasks)) {
            return 1;
        }
        $maxId = max(array_column($tasks, 'id'));
        return $maxId + 1;
    }

    /**
     * Menambah tugas baru ke dalam array
     * 
     * @param array &$tasks Reference ke array tugas
     * @param string $title Judul tugas
     * @return array Tugas yang baru ditambahkan
     */
    function addTask(&$tasks, $title) {
        // Validasi input
        $title = trim($title);
        if (empty($title)) {
            return false;
        }

        $newTask = [
            "id" => getNextId($tasks),
            "title" => $title,
            "status" => "belum"
        ];
        $tasks[] = $newTask;
        return $newTask;
    }

    /**
     * Mengubah judul tugas
     * 
     * @param array &$tasks Reference ke array tugas
     * @param int $taskId ID tugas yang akan diubah
     * @param string $newTitle Judul baru
     * @return bool True jika berhasil, false jika gagal
     */
    function editTask(&$tasks, $taskId, $newTitle) {
        // Validasi input
        $newTitle = trim($newTitle);
        if (empty($newTitle)) {
            return false;
        }

        foreach ($tasks as &$task) {
            if ($task['id'] == $taskId) {
                $task['title'] = $newTitle;
                return true;
            }
        }
        return false;
    }

    /**
     * Mengubah status tugas (toggle antara selesai/belum)
     * 
     * @param array &$tasks Reference ke array tugas
     * @param int $taskId ID tugas yang akan diubah
     * @return bool True jika berhasil, false jika gagal
     */
    function toggleTaskStatus(&$tasks, $taskId) {
        foreach ($tasks as &$task) {
            if ($task['id'] == $taskId) {
                $task['status'] = ($task['status'] == 'selesai') ? 'belum' : 'selesai';
                return true;
            }
        }
        return false;
    }

    /**
     * Menghapus tugas berdasarkan ID
     * 
     * @param array &$tasks Reference ke array tugas
     * @param int $taskId ID tugas yang akan dihapus
     * @return bool True jika berhasil, false jika gagal
     */
    function deleteTask(&$tasks, $taskId) {
        foreach ($tasks as $key => $task) {
            if ($task['id'] == $taskId) {
                unset($tasks[$key]);
                $tasks = array_values($tasks); // Reindex array
                return true;
            }
        }
        return false;
    }

    /**
     * Menampilkan badge status dengan styling Bootstrap
     * 
     * @param string $status Status tugas ('selesai' atau 'belum')
     * @return string HTML badge
     */
    function getStatusBadge($status) {
        $class = ($status == 'selesai') ? 'success' : 'warning';
        $text = ($status == 'selesai') ? 'Selesai' : 'Belum';
        return "<span class='badge bg-{$class} status-badge'>{$text}</span>";
    }

    /**
     * Mendapatkan statistik tugas
     * 
     * @param array $tasks Array tugas
     * @return array Array dengan total, selesai, belum
     */
    function getTaskStatistics($tasks) {
        $total = count($tasks);
        $completed = count(array_filter($tasks, function($task) { 
            return $task['status'] == 'selesai'; 
        }));
        $pending = $total - $completed;
        
        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending
        ];
    }

    // ========================================
    // PROSES FORM SUBMISSION
    // ========================================
    
    $redirectNeeded = false;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    if (!empty($_POST['title'])) {
                        addTask($tasks, $_POST['title']);
                        $redirectNeeded = true;
                    }
                    break;
                    
                case 'edit':
                    if (!empty($_POST['title']) && isset($_POST['task_id'])) {
                        editTask($tasks, $_POST['task_id'], $_POST['title']);
                    }
                    break;
                    
                case 'toggle':
                    if (isset($_POST['task_id'])) {
                        toggleTaskStatus($tasks, $_POST['task_id']);
                    }
                    break;
                    
                case 'delete':
                    if (isset($_POST['task_id'])) {
                        deleteTask($tasks, $_POST['task_id']);
                    }
                    break;
            }
        }
    }

    // Redirect untuk mencegah form resubmission
    if ($redirectNeeded) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Hitung statistik
    $statistics = getTaskStatistics($tasks);
    ?>

    <!-- ======================================== -->
    <!-- HEADER -->
    <!-- ======================================== -->
    <header class="bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Aplikasi To-Do List
                    </h1>
                    <p class="mb-0 mt-2 opacity-75">Kelola tugas Anda dengan mudah dan efisien</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                        <span class="me-3">
                            <i class="fas fa-list-check me-1"></i>
                            Total: <?php echo $statistics['total']; ?> tugas
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container py-4">
        <!-- ======================================== -->
        <!-- FORM TAMBAH TUGAS -->
        <!-- ======================================== -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-plus me-2"></i>
                            Tambah Tugas Baru
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3" id="add-task-form">
                            <input type="hidden" name="action" value="add">
                            <div class="col-md-8">
                                <label for="title" class="form-label">Judul Tugas</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title" 
                                       placeholder="Masukkan judul tugas..." 
                                       required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>
                                    Tambah Tugas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================== -->
        <!-- DAFTAR TUGAS -->
        <!-- ======================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Daftar Tugas
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($tasks)): ?>
                            <!-- Tampilan ketika tidak ada tugas -->
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada tugas</h5>
                                <p class="text-muted">Tambahkan tugas pertama Anda di atas</p>
                            </div>
                        <?php else: ?>
                            <!-- Tabel daftar tugas -->
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="45%">Judul Tugas</th>
                                            <th width="20%">Status</th>
                                            <th width="15%">Checklist</th>
                                            <th width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tasks as $index => $task): ?>
                                            <tr class="task-item <?php echo ($task['status'] == 'selesai') ? 'table-success' : ''; ?>" id="task-row-<?php echo $task['id']; ?>">
                                                <!-- Judul Tugas -->
                                                <td class="align-middle">
                                                    <span class="task-title <?php echo ($task['status'] == 'selesai') ? 'completed' : ''; ?>" id="title-<?php echo $task['id']; ?>">
                                                        <?php echo htmlspecialchars($task['title']); ?>
                                                    </span>
                                                </td>
                                                
                                                <!-- Status -->
                                                <td class="align-middle">
                                                    <span class="task-status" id="status-<?php echo $task['id']; ?>">
                                                        <?php echo getStatusBadge($task['status']); ?>
                                                    </span>
                                                </td>
                                                
                                                <!-- Checkbox Status -->
                                                <td class="align-middle">
                                                    <input type="checkbox" 
                                                           class="form-check-input checkbox-custom task-checkbox" 
                                                           data-task-id="<?php echo $task['id']; ?>"
                                                           <?php echo ($task['status'] == 'selesai') ? 'checked' : ''; ?>
                                                           title="<?php echo ($task['status'] == 'selesai') ? 'Tandai Belum Selesai' : 'Tandai Selesai'; ?>">
                                                </td>
                                                
                                                <!-- Tombol Aksi -->
                                                <td class="align-middle">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- Tombol Edit -->
                                                        <button type="button" 
                                                                class="btn btn-outline-primary btn-action edit-task" 
                                                                data-task-id="<?php echo $task['id']; ?>"
                                                                data-task-title="<?php echo htmlspecialchars($task['title']); ?>"
                                                                title="Edit Tugas">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <!-- Tombol Hapus -->
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-action delete-task" 
                                                                data-task-id="<?php echo $task['id']; ?>"
                                                                title="Hapus Tugas">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================== -->
        <!-- STATISTIK -->
        <!-- ======================================== -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4 class="text-primary" id="total-tasks"><?php echo $statistics['total']; ?></h4>
                                <p class="text-muted mb-0">Total Tugas</p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-success" id="completed-tasks"><?php echo $statistics['completed']; ?></h4>
                                <p class="text-muted mb-0">Selesai</p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-warning" id="pending-tasks"><?php echo $statistics['pending']; ?></h4>
                                <p class="text-muted mb-0">Belum Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================== -->
    <!-- MODAL EDIT TUGAS -->
    <!-- ======================================== -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editTaskModalLabel">
                        <i class="fas fa-edit me-2"></i>
                        Edit Tugas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-task-form">
                        <input type="hidden" id="edit-task-id" name="task_id">
                        <div class="mb-3">
                            <label for="edit-task-title" class="form-label">Judul Tugas</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="edit-task-title" 
                                   name="title" 
                                   placeholder="Masukkan judul tugas baru..." 
                                   required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="save-edit-btn">
                        <i class="fas fa-save me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================== -->
    <!-- FOOTER -->
    <!-- ======================================== -->
    <footer class="bg-dark text-white text-center py-3 mt-5 footer-fixed">
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-code me-2"></i>
                Aplikasi To-Do List - Dibuat dengan PHP dan Bootstrap
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle checkbox toggle
            document.querySelectorAll('.task-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const taskId = this.getAttribute('data-task-id');
                    const isChecked = this.checked;
                    
                    // Update UI immediately
                    updateTaskUI(taskId, isChecked);
                    
                    // Send AJAX request to update server
                    updateTaskStatus(taskId, isChecked);
                });
            });
            
            // Handle edit button
            document.querySelectorAll('.edit-task').forEach(function(button) {
                button.addEventListener('click', function() {
                    const taskId = this.getAttribute('data-task-id');
                    const taskTitle = this.getAttribute('data-task-title');
                    
                    // Set modal values
                    document.getElementById('edit-task-id').value = taskId;
                    document.getElementById('edit-task-title').value = taskTitle;
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
                    modal.show();
                });
            });
            
            // Handle save edit button
            document.getElementById('save-edit-btn').addEventListener('click', function() {
                const taskId = document.getElementById('edit-task-id').value;
                const newTitle = document.getElementById('edit-task-title').value.trim();
                
                if (newTitle === '') {
                    alert('Judul tugas tidak boleh kosong!');
                    return;
                }
                
                // Update UI immediately
                updateTaskTitle(taskId, newTitle);
                
                // Send AJAX request to update server
                updateTaskTitleServer(taskId, newTitle);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
                modal.hide();
            });
            
            // Handle delete button
            document.querySelectorAll('.delete-task').forEach(function(button) {
                button.addEventListener('click', function() {
                    const taskId = this.getAttribute('data-task-id');
                    
                    if (confirm('Yakin ingin menghapus tugas ini?')) {
                        deleteTask(taskId);
                    }
                });
            });
        });
        
        // Update UI immediately without waiting for server response
        function updateTaskUI(taskId, isCompleted) {
            const row = document.getElementById('task-row-' + taskId);
            const title = row.querySelector('.task-title');
            const status = document.getElementById('status-' + taskId);
            
            if (isCompleted) {
                row.classList.add('table-success');
                title.classList.add('completed');
                status.innerHTML = '<span class="badge bg-success status-badge">Selesai</span>';
            } else {
                row.classList.remove('table-success');
                title.classList.remove('completed');
                status.innerHTML = '<span class="badge bg-warning status-badge">Belum</span>';
            }
            
            // Update statistics
            updateStatistics();
        }
        
        // Update task title in UI
        function updateTaskTitle(taskId, newTitle) {
            const titleElement = document.getElementById('title-' + taskId);
            titleElement.textContent = newTitle;
            
            // Update edit button data attribute
            const editButton = document.querySelector(`[data-task-id="${taskId}"].edit-task`);
            editButton.setAttribute('data-task-title', newTitle);
        }
        
        // Send AJAX request to update task status
        function updateTaskStatus(taskId, isCompleted) {
            const formData = new FormData();
            formData.append('action', 'toggle');
            formData.append('task_id', taskId);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            }).catch(function(error) {
                console.error('Error updating task status:', error);
            });
        }
        
        // Send AJAX request to update task title
        function updateTaskTitleServer(taskId, newTitle) {
            const formData = new FormData();
            formData.append('action', 'edit');
            formData.append('task_id', taskId);
            formData.append('title', newTitle);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            }).catch(function(error) {
                console.error('Error updating task title:', error);
            });
        }
        
        // Delete task via AJAX
        function deleteTask(taskId) {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('task_id', taskId);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            }).then(function(response) {
                if (response.ok) {
                    const row = document.getElementById('task-row-' + taskId);
                    row.remove();
                    updateStatistics();
                }
            }).catch(function(error) {
                console.error('Error deleting task:', error);
            });
        }
        
        // Update statistics display
        function updateStatistics() {
            const checkboxes = document.querySelectorAll('.task-checkbox');
            const total = checkboxes.length;
            const completed = document.querySelectorAll('.task-checkbox:checked').length;
            const pending = total - completed;
            
            document.getElementById('total-tasks').textContent = total;
            document.getElementById('completed-tasks').textContent = completed;
            document.getElementById('pending-tasks').textContent = pending;
        }
    </script>
</body>
</html> 