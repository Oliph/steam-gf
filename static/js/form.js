var Form = function()
{
	_self = this;

	this.CurrentInput = undefined;
	this.Form = document.querySelector("#search_form");
	this.ButtonDiv = document.querySelector("#submit_button");

	this.inputsNb = 0;

	this.inputHtml = 
		`<a class="ui label">
			Steam ID
		</a>
		<input id="current_input" type="text" placeholder="Placeholder...">`;

	this.init = function()
	{
		this.createInput(true);
	};

	this.createInput = function(required = false)
	{
		this.inputsNb++;

		let DivInput = document.createElement('div');
		DivInput.className = 'ui labeled input field';
		DivInput.innerHTML = this.inputHtml;

		if (required)
		{
			DivInput.lastChild.required = true;		
		}

		DivInput.lastChild.name = 'id-'+this.inputsNb;

		this.Form.insertBefore(DivInput,this.ButtonDiv);

		this.CurrentInput = DivInput;

		this.CurrentInput.addEventListener('paste', this.checkPaste);
		this.CurrentInput.addEventListener('keydown', this.checkNewInput);
	};

	this.checkPaste = function(e)
	{
		_self.checkNewInput(e, true);
	};

	this.checkNewInput = function(e, paste = false)
	{
		if (e.target.value.length > 5 || paste)
		{
			e.target.removeAttribute('id');
			_self.CurrentInput.removeEventListener('paste', _self.checkPaste);
			_self.CurrentInput.removeEventListener('keydown', _self.checkNewInput);

			console.log(_self.inputsNb);

			if (_self.inputsNb < 4)
			{
				_self.createInput();
			}
		}

	};
}

var Form = new Form();

Form.init();