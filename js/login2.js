function toggleSenha() {
    const senha = document.getElementById("iSenha");
    const icone = document.getElementById("iIcone1");

    if (senha.type === "password") {
        senha.type = "text";
        icone.classList.remove("bi-eye");
        icone.classList.add("bi-eye-slash");
    } else {
        senha.type = "password";
        icone.classList.remove("bi-eye-slash");
        icone.classList.add("bi-eye");
    }
}