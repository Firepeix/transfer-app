@extends('layouts.main-layout')
@section('content')
    <div class="row">
        <div class="col m12" style="text-align: center">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Cadastrar:</span>
                    <div class="row">
                        <div class="input-field col s6">
                            <input placeholder="John Doe" id="name" type="text">
                            <label for="name">Nome Completo</label>
                        </div>
                        <div class="input-field col s6">
                            <input data-mask="###.###.###-##" class="masked" placeholder="000.000.00-00" id="document" type="text">
                            <label for="document">CPF</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="email" type="email">
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password" type="password">
                            <label for="password">Password</label>
                        </div>
                    </div>
                </div>
                <div class="card-action">
                    <a style="width: 100%" onclick="create()" class="waves-effect green waves-light btn">Cadastrar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script>
      window.addEventListener('DOMContentLoaded', onCreateStandardLoad);

      async function onCreateStandardLoad () {
      }

      async function create () {
        const email = document.querySelector('#email');
        const name = document.querySelector('#name');
        const userDocument = document.querySelector('#document');
        const password = document.querySelector('#password');
        if (email && name && userDocument && password) {
          const body = {
            document: userDocument.value.replace(/\D/gm, ''),
            name: name.value,
            password: password.value ,
            email: email.value,
            type: 'standard'
          };
          Swal.fire({
            title: 'Processando!',
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          try {
            const response = await axios.post(`${window.location.origin}/api/users`, body);
            Swal.fire({
              icon: 'success',
              title: response.data.meta.message,
              showConfirmButton: false,
              timer: 1500,
              willClose: () => window.location.reload()
            });
          } catch (error) {
            if (error.response.status === 422) {
              Swal.close()
              Object.values(error.response.data.errors).forEach(errorType => {
                errorType.forEach(error => {
                  const errorToast = Toastify({
                    text: error,
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "center",
                    stopOnFocus: false,
                  });
                  errorToast.options.style.background = '#f44336';
                  errorToast.showToast();
                });
              });
              return;
            }

            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: error.response.data.message
            });
          }
        }
      }

    </script>
@endpush
