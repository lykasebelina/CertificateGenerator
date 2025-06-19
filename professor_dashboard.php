
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>CertiGen - Certificate Generator Dashboard</title>
<!-- Google Material Icons CDN -->
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet" />
<style>
  /* Reset & base */
  * {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: #f0f4f9;
    color: #111827;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    font-size: 16px;
  }
  a {
    text-decoration: none;
    color: inherit;
  }
  button {
    cursor: pointer;
  }

  /* Theme Colors & Gradients */
  :root {
    --blue-gradient-start: #3a7bd5;
    --blue-gradient-end: #00d2ff;
    --blue-dark: #1e40af;
    --blue-light: #eef6ff;
    --primary-text: #111827;
    --sidebar-bg: linear-gradient(180deg, #1a3c72, #2a5298);
    --white: #fff;
    --gray-light: #f9fafb;
  }

  /* Layout Grid and Container */
  .app {
    display: grid;
    grid-template-columns: 280px 1fr;
    grid-template-rows: auto 1fr auto;
    grid-template-areas:
      "sidebar header"
      "sidebar main"
      "sidebar footer";
    min-height: 100vh;
  }

  /* Sidebar */
  .sidebar {
    grid-area: sidebar;
    background: var(--sidebar-bg);
    color: var(--white);
    display: flex;
    flex-direction: column;
    padding: 32px 24px 24px;
    border-top-right-radius: 16px;
    border-bottom-right-radius: 16px;
  }
  .sidebar .brand {
    font-weight: 900;
    font-size: 1.8rem;
    margin-bottom: 4px;
  }
  .sidebar .tagline {
    font-size: 0.875rem;
    font-weight: 400;
    color: #b0c4decc;
    margin-bottom: 40px;
  }
  .nav-links {
    list-style: none;
    padding: 0;
    margin: 0;
    flex-grow: 1;
  }
  .nav-links li {
    margin-bottom: 16px;
  }
  .nav-links li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 600;
    color: var(--white);
    transition: background-color 0.3s ease;
  }
  .nav-links li a:hover,
  .nav-links li a.active {
    background: linear-gradient(90deg, var(--blue-gradient-start), var(--blue-gradient-end));
    box-shadow: 0 4px 12px rgba(0, 210, 255, 0.4);
  }
  .nav-links li a .material-icons {
    font-size: 20px;
  }
  .nav-footer {
    font-weight: 700;
    cursor: pointer;
    padding: 12px 16px;
    margin-top: auto;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: background-color 0.3s ease;
  }
  .nav-footer:hover {
    background: linear-gradient(90deg, var(--blue-gradient-start), var(--blue-gradient-end));
    box-shadow: 0 4px 12px rgba(0, 210, 255, 0.4);
  }

  /* Header */
  header {
    grid-area: header;
    padding: 24px 48px;
    background: var(--white);
    border-bottom: 1px solid #e5e7eb;
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--primary-text);
  }

  /* Main Content */
  main {
    grid-area: main;
    padding: 32px 48px;
    background: var(--gray-light);
    overflow-y: auto;
  }

  /* Dashboard Overview Cards */
  .overview {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
    margin-bottom: 48px;
  }
  .card {
    flex: 1 1 220px;
    padding: 24px;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    font-weight: 700;
    font-size: 1.3rem;
    box-shadow: 0 6px 12px rgb(58 123 213 / 0.12);
    min-width: 220px;
  }
  .card.green {
    background: #daf5d7;
    color: #27632a;
  }
  .card.blue {
    background: #d7e6fc;
    color: #244a93;
  }
  .card.yellow {
    background: #fff5cc;
    color: #b48600;
  }
  .card .number {
    font-size: 2.8rem;
    margin-top: 8px;
    font-weight: 900;
  }

  /* Audit Logs Table */
  section.audit-logs {
    background: var(--white);
    border-radius: 16px;
    padding: 24px 32px;
    box-shadow: 0 4px 12px rgb(58 123 213 / 0.1);
  }
  section.audit-logs h2 {
    font-weight: 700;
    margin-bottom: 16px;
    font-size: 1.4rem;
    color: var(--primary-text);
  }
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
  }
  thead {
    background: var(--blue-gradient-start);
    color: var(--white);
  }
  thead th {
    text-align: left;
    padding: 12px 16px;
    font-weight: 700;
  }
  tbody tr {
    border-bottom: 1px solid #e5e7eb;
  }
  tbody tr:nth-child(even) {
    background: #f9fafb;
  }
  tbody td {
    padding: 12px 16px;
    color: #374151;
  }

  /* Responsive Breakpoints */

  /* Mobile */
  @media (max-width: 767px) {
    .app {
      grid-template-columns: 1fr;
      grid-template-rows: auto auto 1fr auto;
      grid-template-areas:
        "header"
        "breadcrumb"
        "main"
        "footer";
    }

    .sidebar {
      position: fixed;
      top: 0; left: 0; bottom: 0;
      width: 280px;
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      z-index: 1001;
      border-radius: 0 16px 16px 0;
      padding-top: 48px;
    }
    .sidebar.show {
      transform: translateX(0);
      box-shadow: 5px 0 15px rgba(0,0,0,0.15);
    }
    .hamburger {
      cursor: pointer;
      background: transparent;
      border: none;
      font-size: 32px;
      display: flex;
      align-items: center;
      color: var(--primary-text);
    }
    header {
      position: sticky;
      top: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 16px;
      font-size: 1.2rem;
      background: var(--white);
      border-bottom: 1px solid #e5e7eb;
    }
    main {
      padding: 24px 16px;
      margin-top: 12px;
    }
    .overview {
      flex-direction: column;
      gap: 16px;
    }
  }

  /* Desktop and Up */
  @media (min-width: 768px) {
    .hamburger {
      display: none;
    }
  }

  /* Large Desktop */
  @media (min-width: 1440px) {
    main {
      max-width: 1400px;
      margin-left: auto;
      margin-right: auto;
    }
  }

</style>
</head>
<body>
<div class="app">
  <aside class="sidebar" id="sidebar" aria-label="Primary Sidebar Navigation">
    <div class="brand" aria-label="Site Logo and Brand Name">
      CertiGen
    </div>
    <div class="tagline">Professor Panel</div>
    <nav role="navigation" aria-label="Main Navigation">
      <ul class="nav-links">
        <li><a href="#" class="active" aria-current="page"><span class="material-icons" aria-hidden="true">dashboard</span> Dashboard</a></li>

        <li><a href="display_certificates.php"><span class="material-icons" aria-hidden="true">badge</span> Certificates</a></li>
        <li><a href="#"><span class="material-icons" aria-hidden="true">history</span> Audit Logs</a></li>
        <li><a href="certificate-generator.php"><span class="material-icons" aria-hidden="true">file_download</span> Generate Certificate</a></li>

      </ul>
    </nav>
    <button class="nav-footer" id="logoutBtn" aria-label="Logout Button">
      <span class="material-icons" aria-hidden="true">logout</span> Logout
    </button>
  </aside>

  <header>
    <button class="hamburger" id="menuToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="sidebar">
      <span class="material-icons">menu</span>
    </button>
    Dashboard Overview
  </header>

  <main>
    <section class="overview" aria-label="Dashboard overview statistics">
      <div class="card green" role="region" aria-labelledby="totalAdminsTitle">
        <div>Total Admins</div>
        <div class="number" id="totalAdminsTitle">5</div>
      </div>
      <div class="card blue" role="region" aria-labelledby="totalCertificatesTitle">
        <div>Total Certificates</div>
        <div class="number" id="totalCertificatesTitle">150</div>
      </div>
      <div class="card yellow" role="region" aria-labelledby="recentLogsTitle">
        <div>Recent Logs (Last Hour)</div>
        <div class="number" id="recentLogsTitle">3</div>
      </div>
    </section>

    <section class="audit-logs" aria-label="Recent audit logs">
      <h2>Recent Audit Logs</h2>
      <table>
        <thead>
          <tr>
            <th scope="col">TIME</th>
            <th scope="col">ACTION</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2025-06-18 10:00:00</td>
            <td>Generated certificate for Alice</td>
          </tr>
          <tr>
            <td>2025-06-18 09:30:00</td>
            <td>Admin Bob added new user</td>
          </tr>
          <tr>
            <td>2025-06-18 08:45:00</td>
            <td>Verified certificate for Charlie</td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>
</div>

<script>
  // Toggle mobile sidebar menu
  const menuToggle = document.getElementById('menuToggle');
  const sidebar = document.getElementById('sidebar');

  menuToggle.addEventListener('click', () => {
    const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!expanded));
    sidebar.classList.toggle('show');
  });

  // Simple logout button click placeholder
  document.getElementById('logoutBtn').addEventListener('click', () => {
    alert('Logout clicked - implement logout functionality here.');
  });
</script>
</body>
</html>

