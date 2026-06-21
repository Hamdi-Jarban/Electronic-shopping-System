document.addEventListener('DOMContentLoaded',function(){
document.addEventListener('click',function(event){
if(event.target && event.target.classList.contains('add-to-cart-btn'))
{
    event.preventDefault();
    const button = event.target;
    const variantId = event.getAttribute('data-variant-id');
    const crsfToken = document.querySelector('meta[name="csrf-tokewn"]').getAttribute('content');

    fetch('/cart/add',{
method :'POST',
headers:{
    'Content-Type':'application/json',
    'X-CSRF-TOKEN':crsfToken,
    'Accept':'application/json'
},
body:JSON.stringify({
    variant_id:variantId,
    quantity:1
})
    }).then(respone=>{
        if(!respone.ok){
            throw new Error('حدث خطا في استجابه الخادم');
        }
        return respone.json();
    })

}
});
});