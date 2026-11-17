class AddNumbers {
  constructor(value1, value2) {
    this.value1 = Number(value1) || 0;
    this.value2 = Number(value2) || 0;
  }

  sumNumbers() {
    return this.value1 + this.value2;
  }
}

function sumNumbers1() {
  const elNum1 = document.getElementById("txtFirstNumber");
  const elNum2 = document.getElementById("txtSecondNumber");
  const result = document.getElementById("result");

  if (!elNum1 || !elNum2 || !result) {
    return;
  }

  const num1 = parseFloat(elNum1.value) || 0;
  const num2 = parseFloat(elNum2.value) || 0;

  const obj = new AddNumbers(num1, num2);
  result.innerText = "La suma es: " + obj.sumNumbers();
}
