<!-- HeaderView.php -->
<header class="header">
    <div class="ae">
        <a href="https://ae.utbm.fr/">
            <img src="../assets/ae.png" alt="Logo AE">
        </a>
    </div>
    <div class="utbm">
        <a href="https://www.utbm.fr/">
            <img src="../assets/utbm.svg">
        </a>
    </div>
    <div class="utx">
        <a href="/">
            <img src="../assets/utx.png" alt="Logo Principal UTX">
        </a>
    </div>
    <div class="utx_text">
        <a href="/">
            <img src="../assets/utx_text.png" alt="Logo utx">
        </a>
    </div>
    <div class="logout">
        <a href="logout.php">Déconnexion</a> <!-- Link to logout PHP script -->
        <a href="account.php">Mon Compte</a> <!-- Link to account PHP script -->
    </div>
</header>

<style>
    .header {
        display: flex;
        justify-content: space-between; /* Align items horizontally */
        align-items: center;
        background-color: #242038; /* Blue header background */
        color: #CAC4CE;
        text-align: center;
        padding: 10px; /* Reduce the top and bottom padding */
        border-radius: 10px 10px 10px 10px; /* Rounded bottom corners */
    }

    .header img {
        max-width: 40px;
        height: auto;
    }

    .utbm img {
        max-width: 80px;
        height: auto;
    }

    .header .text {
        flex: 1; /* Fill available space */
    }

    .header .logout {
        display: flex; /* Display links in a row */
        flex-direction: column; /* Stack links vertically */
        align-items: center; /* Center links horizontally */
    }

    .header .logout a {
        text-decoration: none;
        color: #CAC4CE;
        margin-top: 5px; /* Add space between the links */
    }
</style>
