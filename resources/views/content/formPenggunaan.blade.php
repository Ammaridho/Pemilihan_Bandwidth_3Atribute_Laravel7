
    <script>console.log($jmlpenghuni);</script>

    <div id="parentPengguna">
      <h4 class="text-center">Banyak Gadget</h4>
      @for ($i = 1; $i <= $jmlpenghuni; $i++)

        <div class="form-row">
          {{-- <div class="form-group col-7">
            <label for="nama{{$i}}">{{$i}}.Nama</label>
            <input type="text" class="nama form-control" id="nama{{$i}}" placeholder="Nama Penghuni" name="nama{{$i}}"  onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" required>
          </div> --}}

          {{-- <input class="nama form-control" type="hidden" id="nama{{$i}}" name="nama{{$i}}" value="nama{{$i}}"> --}}

          {{-- <div class="form-group col-7">
            <label for="penghuni{{$i}}">Penghuni {{$i}}</label>
            <input type="text" class="penghuni form-control" id="penghuni{{$i}}" placeholder="penghuni Penghuni" name="penghuni{{$i}}"  onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" required>
          </div> --}}

          <div class="form-group col-12">
            <label class="text-center" for="banyakGadget{{$i}}">Penghuni {{$i}}</label>
            <input id="banyakgadget{{$i}}" type="number" name="banyakGadget{{$i}}" class="banyakgadget form-control" placeholder="" min="0" max="20" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
          </div>
        </div>

        @endfor
    </div>

    <script>
      var ke = {{$jmlpenghuni}};
      
      var bg = []; //array untuk mengambil berapa banyak gadget yang dimiliki perorang

      var nama = [];
      

      $("#parentPengguna").find('.banyakgadget').on("change keyup",function () {

        $("parentPengguna").children("input").each(function() {
          if($(this).val() === ""){
            console.log("Empty Fields!!");
          }else{
            console.log("Not Empty");
          }
        });

        for (var i = 1; i <= ke; i++) {  
          bg[i-1] = $(`#banyakgadget${i}`).val();
          nama[i-1] = $(`#nama${i}`).val();
        }

        $.get("{{route('gadget')}}",{ke:ke,bg:bg}, function(data){
          $("#formgadget").html(data);
        });
      });

    </script>


      



    

