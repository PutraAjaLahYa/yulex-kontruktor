  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 60px;
    height: 100vh;
    background-color: #2c3e50;
    transition: width 0.3s ease;
    overflow-x: hidden;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .sidebar:hover {
    width: 220px;
  }

  .sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .sidebar ul li {
    display: flex;
    align-items: center;
    padding: 20px 10px;
    color: #ecf0f1;
    cursor: pointer;
    transition: background 0.2s;
  }

  .sidebar ul li:hover {
    background-color: rgb(8, 131, 255);
  }

  .sidebar ul li i {
    font-size: 20px;
    width: 40px;
    text-align: center;
  }

  .sidebar ul li span {
    opacity: 0;
    white-space: nowrap;
    transition: opacity 0.3s;
  }

  .sidebar:hover ul li span {
    opacity: 1;
    margin-left: 5px;
  }

  /* Responsive: ubah sidebar jadi navbar atas */
  @media (max-width: 768px) {
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 60px;
      flex-direction: row;
      align-items: center;
      justify-content: center;
      padding: 0;
    }

    .sidebar:hover {
      width: 100%;
    }

    .sidebar ul {
      display: flex;
      flex-direction: row;
      justify-content: space-around;
      align-items: center;
      width: 100%;
    }

    .sidebar ul li {
      flex-direction: column;
      justify-content: center;
      padding: 10px;
    }

    .sidebar ul li span {
      display: none;
    }
  }
</style>
  
<!-- Sidebar HTML -->
<div class="sidebar">
  <ul>
    <li onclick="location.href='dashboard.php'">
      <i class="fas fa-home"></i>
      <span>Dashboard</span>
    </li>
    <li onclick="location.href='input_barang.php'">
      <i class="fas fa-box-open"></i>
      <span>Input Barang</span>
    </li>
    <li onclick="location.href='input_karyawan.php'">
      <i class="fas fa-user-plus"></i>
      <span>Input Karyawan</span>
    </li>
    <li onclick="location.href='absen_karyawan.php'">
      <i class="fas fa-clipboard-check"></i>
      <span>Absen Karyawan</span>
    </li>
    <li onclick="location.href='laporan_absen.php'">
      <i class="fas fa-file-alt"></i>
      <span>Laporan Absen</span>
    </li>
    <li onclick="location.href='logout.php'">
      <i class="fas fa-sign-out-alt"></i>
      <span>Logout</span>
    </li>
  </ul>
</div>

<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">