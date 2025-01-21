
//////////// ВИВІД МАРОК ЗРОБИТЬ!
$(function(){
    
    showMarka(
        //alert(1)
    )
    show_allCars()
    
})

function show_all(){
    $.getJSON("automobile.php?mode=out_all", function(json){
        if(json.FormData.length > 0){
               
        }
    })
}
//////////////// вивести всі авто в таблицю яку створити



    function showMarka(){
      
        $.getJSON("automobile.php?mode=out_marka", function (json) {
           // console.log(json);
                if(json.marka.length > 0){
    
                    console.log(json)
                    $.each(json.marka, function(){
                        $("#select_marka").append("<option value = '"+ this.id +"'>" + this.name + " </option>")
                       })
                }    
            
    
            }
            
        );
    
    }


    $("#add_auto").on("click", function(){
        
       /* let modelVal = $("#model_auto").val()
        let select_markaVal = $("#select_marka").val()
        let priceVal = $("#price_auto").val()
        let odometrVal = $("#odometr").val()
        let descriptionVal = $("#description").val()
        let countVal = $("#count").val()
        */
       


     /*   if(modelVal && select_markaVal && priceVal && odometrVal && descriptionVal && countVal){

          /*  $.post("automobile.php",{
                model_auto: modelVal,
                select_marka: select_markaVal,
                price_auto: priceVal,
                odometr: odometrVal,
                description: descriptionVal,
                count: countVal
            },
                function (result) {
                    if(result.status == "success"){
                        alert(result.message)
                    } if(result.status == "fail"){
                        alert(result.message)
                    }
                },
                "json"
            );
            
            




    

      
        } 

       */ 
            add_catalog()
            //add_editPhoto()
        
    })


    function add_catalog(){
        let photo = $("#file_inp").prop("files")[0];  ////// коли користувач оберає файл ми берем перший файл у змінну фото
        let add_form = $("#form").serializeArray(); ///  з усих елементів форми дістаєм значення і добавляємо у віртуальний масив
        console.log(add_form)
        let formdata = new FormData(); ///  створюємо обєкт віртуальної форми
        formdata.append("file_inp", photo);  ///  додаємо файл до форми
        $(add_form).each(function(index, elem) { ///  за доп цикла перебираємо дані і добавляємо в форму (ключ - значення)
            formdata.append(elem.name, elem.value);
        })

        console.log(formdata)
        
        $.ajax({
            type: "POST", 
            url: "automobile.php",
            data: formdata,
            contentType: false,  /// не перевіряємо завантажуємий контент (сервер)  
            processData: false, ///  процес передачі даних не обмежується 
            dataType: "json",
            success: function (result) {
                if(result.status == "success"){
                    alert(result.message)
                } if(result.status == "fail"){
                    alert(result.message)
                }
            }
        });



    }

    function show_allCars(){
        $.getJSON("automobile.php?mode=out_cars", function(json){
            
            if(json.automobiles.length > 0){
                $.each(json.automobiles, function(){
                    $("#show_automobile").append("<tr>" + "<td>" + this.id_marka + "</td>"  + "<td id = '" + this['id'] + "model'> " + this.model + "</td>" + "<td>" + this.odometr + "</td>" + "<td>" + this.full_description + "</td>" + "<td>" + this.count + "</td>" + "<td><img src='images/" + this.photo + "' alt='Фото' width='100'></td>" + "<td>" + this.price + "</td>" +  "<td>" + "<button id = '" + this['id'] + "' class='edit'>Edit</button> </td>" + "<td>" + "<button id = '" + this['id'] + "'delete' class = 'delete'>Delete</button> </td>" + "</tr>")
                    
                })
            }
            
        })
    }

//////////////////////////
//////////          ПОДИВИТИСЬ РЕДАГУВАННЯ ТА ПОСТАВИТИ РЕАЛЬНІ МАШИНИ

   


    $("#show_automobile").on("click", ".delete", function(){
        
        let id = parseInt($(this).attr("id"))
        let elemModel = ("#" + id + 'model')
        //alert(elemModel)
        let stringModel = $(elemModel).text()
        $("#text_delete").text("Ви дійсно хочете видалити цей автомобіль" + stringModel + "?")
        $("#delete_info").show()
        $("#car_id").val(id)
       // alert(id)

        
    })

    $("#Submit").on("click", function(){
        let val = $("input[type=radio]:checked").val()
        let id = $("#car_id").val()
        alert(id)
        if(val == "YES"){
            $.getJSON("automobile.php?mode=del_car&id=" + id, function(json){
                 if(json.status == "success"){
                    $("#delete_info").text("Авто видалено!")
                    show_allCars()
                 } else {
                    $("#delete_info").text("Авто не видалилось!")
                 }
            })
        } else {
            alert("Видалення не спрацювало")
        }
    })


    

    $("#show_automobile").on("click", ".edit", function() {
        
        let id = parseInt($(this).attr("id"));
        
        $.getJSON("automobile.php?mode=edit_cars&id=" + id, function(json) {
            $("#change_select_model").empty();
     
            console.log(json.car);
            
            
            if (json.status == "fail") {
                alert("Автомобіль не відредагований!");
            } 
            if (json.car && json.car.id) {
                
                $.getJSON("automobile.php?mode=out_marka", function(markaJson) {
                    if (markaJson.marka.length > 0) {
                        $.each(markaJson.marka, function() {
                            $("#change_select_model").append("<option value='" + this.id + "'>" + this.name + "</option>");
                            
                        });

                        $("#change_select_model").val(json.car.id_marka);
                        
                    }
                });

                $("#change_model").val(json.car.model);
                $("#change_select_model").val(json.car.id_marka);
                $("#change_price").val(json.car.price);
                $("#change_odometr").val(json.car.odometr);
                $("#change_description").val(json.car.full_description);
                $("#change_count").val(json.car.count);
                //alert(id)
                $("#change_car_id").val(id);
                $("#id_image").attr("src", "images/" + json.car.photo)



              

               

                $("#change_info").show(); 
            } else {
                alert("Дані автомобіля не знайдено!");
            }
        })
    });

    
    
        $("#change_button").on("click", function(){
          /*  let model = $("#change_model").val()
            let select = $("#change_select_model").val()
            let price = $("#change_price").val()
            let odometr = $("#change_odometr").val()
            let description = $("#change_description").val()
            let count = $("#change_count").val()

          // let photo = $("#file_inp_img").val(json.car.photo)

            let carId = $("#car_id").val()
             //alert(carId)
            
            $.getJSON("automobile.php?mode=result_edit&id=" + carId + "&id_marka=" + select + "&model=" + model + "&odometr=" + odometr + "&full_description=" + description + "&count=" + count + "&photo=" + photo + "&price=" + price)
                if(json.car.length > 0){
                   
                   
                    
                   
                    if(json.status == "success"){
                        alert("Дані відредаговані!")
                    } if(json.status == "fail"){
                        alert(json.message)
                    }
                }
            

       */
      
            add_editPhoto()
            }) 


        function add_editPhoto(){
            let photEdit = $("#file_inp_img").prop("files")[0];
            let change_form = $("#form_edit").serializeArray(); 
            console.log(change_form)
            let formdataEdit = new FormData(); 
            formdataEdit.append("file_inp_img", photEdit);  
            $(change_form).each(function(index, elem) { 
            formdataEdit.append(elem.name, elem.value);
        })
        
        
        $.ajax({
            type: "POST", 
            url: "automobile.php",
            data: formdataEdit,
            contentType: false, 
            processData: false,  
            dataType: "json",
            success: function (result) {
                alert(result.status)
                if(result.status == "success"){
                    
                    alert(result.message)
                } if(result.status == "fail"){
                    alert(result.message)
                }
            }
        });



        }
    
        
    


///////////////////// ЗРОБИТИ ВИДАЛЕННЯ БЕЗ ФОТО