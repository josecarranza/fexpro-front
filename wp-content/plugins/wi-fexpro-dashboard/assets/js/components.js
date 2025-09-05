 
angular.module('fex-components',[])
	.component('fexLoading', {
		template: `<div class="progress-box {{$ctrl.finish?'finish':''}}">
		<label class="processing" for="">Processing...</label>
		<label class="congratulation" for="">Congratulations!</label>
		<div class="progress-bar1"></div>
		<p>Your data has been successfully exported.</p>
	</div>`,
		bindings: {
			finish: "=",
			
		},
		controller: function($scope) {
			var $ctrl = this;
			
		}
	});
    
 