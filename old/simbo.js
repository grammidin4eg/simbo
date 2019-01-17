function BLRes(result, columns) {
   this.cols = columns;
   this.error = result.ERROR;
   this.result = result.RESULT;
   this.data = result.DATA;
   this.count = 0;
   this.list = [];
   if( result.DATA !== 'NODATA' ) {
      this.count = result.DATA.COUNT;
      if( result.DATA.LIST ) {
         for(var i = 0;i < result.DATA.LIST.length; i++) {
            var cur = result.DATA.LIST[i];
            this.list.push(new BLRec(cur, this.cols));
         }
      }
   }
}

BLRes.prototype.getList = function() {
   return this.list;
}

BLRes.prototype.at = function(index) {
   return this.list[index];
}

function BLRec(resArray, columns) {
   this.cols = columns;
   this.data = resArray;
}

BLRec.prototype.get = function(field) {
   var index = this.cols.indexOf(field);
   if( index < 0) {
      return null;
   }
   return this.data[index];
}
