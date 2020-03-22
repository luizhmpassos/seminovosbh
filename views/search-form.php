<div class="container">
  <h1> Buscar por ofertas </h1>

  <div class="grid">
  <form action="index.php?page=result" method="POST">

    <div class="row">
      <div class="col-md-3 col-md-offset-3">      
        <label> Tipo </label>
      </div>
      <div class="col-md-3">
        <select name="tipo">
          <option value="carro">Carro</option>
          <option value="moto">Moto</option>
          <option value="caminhao">Caminhão</option>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 col-md-offset-3">      
        <label> Marca </label>
      </div>
      <div class="col-md-3">
				<input type="text" name="marca" /> 
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 col-md-offset-3">      
        <label> Modelo </label>
      </div>
      <div class="col-md-3">
				<input type="text" name="modelo" />
        </div>
    </div>

    <div class="row">
      <div class="col-md-3 col-md-offset-3">      
        <label> Ano De: </label>
      </div>
      <div class="col-md-3">
        <input type="text" name="ano_inicio" /> 
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 col-md-offset-3">      
        <label> Ano Até: </label>
      </div>
      <div class="col-md-3">
				<input type="text" name="ano_final" />
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
				<input type="submit" value="Buscar" />
      </div>
    </div>

  </form>
  </div>
</container>