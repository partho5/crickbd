var score=new Vue({
   el:"#score",
    data:{
       first_inn:true,
       second_inn:false
    },
    methods:{
       showFirst:function () {
           this.first_inn=true;
           this.second_inn=false;
       },
        showSecond:function () {
            this.first_inn=false;
            this.second_inn=true;
        }
    }

});