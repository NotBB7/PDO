<footer class="footer mt-auto py-4 text-center shadow-lg">
    <a class="text-decoration-none" href="../controllers/login-controller.php">Afpa - DWWM - 2023</a>
    <a class="text-decoration-none" href="../controllers/login-controller.php"><p>Mentions légales</p></a>

    <!-- nous faisons apparaitre un lien de déconnexion uniquement si l'utilisateur est connecté -->
    <?php if (isset($_SESSION['user'])) { ?>
        <a class="text-decoration-none text-white d-block mt-1" href="../controllers/disconnection-controller.php">- Déconnexion -</a>
    <?php } ?>

</footer>
<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
</body>

</html>