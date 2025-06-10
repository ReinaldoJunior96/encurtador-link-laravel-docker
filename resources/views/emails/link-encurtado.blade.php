@component('mail::message')
# Obrigado pelo seu pagamento!

Seu pagamento foi aprovado com sucesso. Aqui está o seu link encurtado:

@component('mail::panel')
[{{ $shortUrl }}]({{ $shortUrl }})
@endcomponent

Você pode acessar o link original a qualquer momento:

[{{ $originalUrl }}]({{ $originalUrl }})

O link encurtado expira em 1 minuto após a criação.

Se precisar de outro link, basta acessar nosso site novamente!

Obrigado por usar nosso serviço 🚀

@endcomponent
