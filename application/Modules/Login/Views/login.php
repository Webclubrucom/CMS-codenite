<main class="form-signin w-100 m-auto">
    <form class="text-center">
        <img class="mb-4" src="/application/themes/backend/default/assets/img/logo.webp" alt="">
        <h1 class="h3 mb-3 fw-normal text-center"><?= $title ?></h1>

        <div class="form-floating">
            <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
            <label for="email">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" class="form-control" id="password" placeholder="Напишите пароль" autocomplete="">
            <label for="password">Пароль</label>
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit">Войти</button>
        <p class="mt-5 mb-3 text-body-secondary text-center">&copy; <?php echo date('Y') ?></p>
    </form>
</main>