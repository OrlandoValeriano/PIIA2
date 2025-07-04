document.addEventListener("DOMContentLoaded", function () {
  const carreraSelect = document.getElementById("carreraSelect");
  const carreraCarreraId = document.getElementById("carrera_carrera_id");

  carreraSelect.addEventListener("change", function () {
    const selectedValue = this.value;

    // Establece el mismo valor en el segundo select
    carreraCarreraId.value = selectedValue;
  });
});
