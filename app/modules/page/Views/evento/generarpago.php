<?php
$nombre    = html_entity_decode($this->nombre);
$correo    = html_entity_decode($this->correo);
$cedula    = html_entity_decode($this->cedula);
$descripcion = htmlspecialchars($this->descripcion, ENT_QUOTES, 'UTF-8');
?>
<div class="d-none">
  <form>
    <script src="https://checkout.epayco.co/checkout.js" class="epayco-button"
      data-epayco-key="<?= $this->payment['PUBLIC_KEY'] ?>"
      data-epayco-amount="<?= $this->totalFinal ?>"
      data-epayco-name="Pago Galería Café Libro"
      data-epayco-description="<?= $descripcion ?>"
      data-epayco-currency="cop"
      data-epayco-country="co"
      data-epayco-address-billing="<?= htmlspecialchars($correo, ENT_QUOTES) ?>"
      data-epayco-name-billing="<?= htmlspecialchars($nombre, ENT_QUOTES) ?>"
      data-epayco-mobilephone-billing=""
      data-epayco-number-doc-billing="<?= htmlspecialchars($cedula, ENT_QUOTES) ?>"
      data-epayco-test="false"
      data-epayco-autoclick="true"
      data-epayco-external="false"
      data-epayco-extra1="<?= (int) $this->idcompra ?>"
      data-epayco-extra2="<?= (int) $this->cantidadTotal ?>"
      data-epayco-extra3="<?= $this->promoId ? (int) $this->promoId : '' ?>"
      data-epayco-extra4="<?= htmlspecialchars(html_entity_decode($this->codigo ?? ''), ENT_QUOTES) ?>"
      data-epayco-response="<?= $this->payment['responseUrl'] ?>"
      data-epayco-confirmation="<?= $this->payment['confirmationUrl'] ?>"
      data-epayco-implementation-type="script">
    </script>
  </form>
</div>
