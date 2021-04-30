@extends('layouts.main-layout')
@section('content')
    <div class="row">
        <div class="col m4">
            <div class="card" style="text-align: center">
                <div class="card-content">
                    <span class="card-title">Transferir de:</span>
                    <select onchange="setAmount('#payerId')" name="payerId" id="payerId" title="payerId">
                        <option value="0">Selecionar Pagador</option>
                    </select>
                </div>
                <div class="card-action" style="border: none; padding-top: 0">
                    <div>
                        <div style="font-weight: bold; font-size: 2.39rem;opacity: 0" class="blue-text balance bold">R$ 10,45</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col m4">
            <div class="card" style="text-align: center">
                <div class="card-content">
                    <span class="card-title">Valor:</span>
                    <input title="value" value="0,00" class="money" id="value" type="text">
                </div>
                <div class="card-action">
                    <a style="width: 100%" onclick="transfer()" class="waves-effect green waves-light btn">Enviar</a>
                </div>

            </div>
        </div>
        <div class="col m4">
            <div class="card" style="text-align: center">
                <div class="card-content">
                    <span class="card-title">Para:</span>
                    <select onchange="setAmount('#payeeId')" name="payeeId" id="payeeId" title="payeeId">
                        <option value="0">Selecionar Beneficiário</option>
                    </select>
                </div>
                <div class="card-action" style="border: none; padding-top: 0">
                    <div>
                        <div style="font-weight: bold; font-size: 2.39rem;opacity: 0" class="blue-text balance bold">R$ 10,45</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script>
      window.addEventListener('DOMContentLoaded', onTransferLoad);

      async function onTransferLoad () {
        SimpleMaskMoney.setMask('.money');
        const users = await getUsers();
        document.querySelectorAll('.balance').forEach(balance => {
          balance.style.opacity = '0'
        })

        setPayerUsers(users.filter(user => user.type === 'standard'))
        setPayeeUsers(users)
      }

      async function getUsers () {
        const response = await axios.get(`${window.location.origin}/api/users`, { params: { include: 'wallet' } });
        return response.data.data;
      }

      function createUserOption (user) {
        const option = new Option(user.name, user.id)
        option.setAttribute('data-balance', user.wallet.data.balance)
        return option;
      }

      function setPayerUsers (users) {
        const options = users.map(user => createUserOption(user))
        const select = document.querySelector('#payerId')
        if (select !== null) {
          const instance = M.FormSelect.getInstance(select);
          instance.destroy()

          const oldOptions = new Array(...select.querySelectorAll('option'));
          oldOptions.slice(1).map(option => option.remove())
          options.forEach(option => select.appendChild(option))
          M.FormSelect.init(select, {  });
        }
      }
      function setPayeeUsers (users) {
        const options = users.map(user => createUserOption(user))
        const select = document.querySelector('#payeeId')
        if (select !== null) {
          const instance = M.FormSelect.getInstance(select);
          instance.destroy()

          const oldOptions = new Array(...select.querySelectorAll('option'));
          oldOptions.slice(1).map(option => option.remove())
          options.forEach(option => select.appendChild(option))
          M.FormSelect.init(select, {  });
        }
      }

      function setAmount (type) {
        const payer = document.querySelector(type)
        if (payer !== null) {
          const balance = payer.parentNode.parentNode.parentNode.querySelector('.balance')
          if (balance !== null && payer.value !== '0') {
            const balanceAmount = payer.querySelector(`[value="${payer.value}"]`)
            if (balanceAmount !== null) {
              balance.innerHTML = 'R$ ' + toReal(balanceAmount.getAttribute('data-balance'))
              balance.style.opacity = 1;
            }
            return;
          }
          if (balance !== null) {
            balance.style.opacity = '0';
          }
        }
      }

      async function transfer () {
        const payer = document.querySelector('#payerId')
        const payee = document.querySelector('#payeeId')
        const value = document.querySelector('#value')
        if (payer && payee && payer.value !== '0' && payee.value !== '0') {
          const body = {value: Number(value.value.replace(/\D/gm, '')), payeeId: payee.value, payerId: payer.value}
          Swal.fire({
            title: 'Processando!',
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading()
            }
          })
          try {
            const response = await axios.post(`${window.location.origin}/api/transaction`, body)
            Swal.fire({
              icon: 'success',
              title: response.data.meta.message,
              showConfirmButton: false,
              timer: 1500,
              willClose: () => onTransferLoad()
            })
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
            })
          }
        }
      }

    </script>
@endpush
