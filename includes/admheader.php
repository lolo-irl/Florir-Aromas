<?php
?>
<header class="admin-header-nav">
    <div class="header-content">
        <div class="logo">
            <span></span>
            <h2>Florir Admin</h2>
        </div>
        <nav class="admin-nav">
            <a href="../pages/admpage.php" class="nav-link active">Dashboard</a>
            <a href="../produtos/list.php" class="nav-link">Produtos</a>
            <a href="../pedidos/gerenciar.php" class="nav-link">Pedidos</a>
            <a href="../clientes/list.php" class="nav-link">Clientes</a>
            <a href="../index.php" class="nav-link" target="_blank">Ver Site</a>
            <a href="?logout=true" class="nav-link logout">Sair</a>
        </nav>
    </div>
</header>

<style>
.admin-header-nav {
    background: var(--primary);
    color: white;
    padding: 1rem 2rem;
    box-shadow: var(--shadow);
}

.header-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 1.2rem;
}

.admin-nav {
    display: flex;
    gap: 1.5rem;
    align-items: center;
}

.nav-link {
    color: white;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: background 0.3s ease;
    font-weight: 500;
}

.nav-link:hover,
.nav-link.active {
    background: rgba(255,255,255,0.1);
}

.nav-link.logout {
    background: var(--error);
}

.nav-link.logout:hover {
    background: #d32f2f;
}
</style>