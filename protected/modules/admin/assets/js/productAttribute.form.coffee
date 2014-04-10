
$ ->
	field_type_input = $('#ProductAttribute_field_type')
	variants_block = $('#variants')
	variants_input = $('#ProductAttribute_variants')
	variants_rows = $('.variant_rows', variants_block)
	checkbox_variants_rows = $('.checkbox_variants', variants_block)
	add_variant_button = $('.add_variant', variants_block)

	variant_values = {}
	update_variants = () ->
		if field_type_input.val() == 'checkbox'
			block = checkbox_variants_rows
		else
			block = variants_rows

		inputs = $('.control-row input', block)
		variant_values = {}
		counter = 0
		for input in inputs
			input = $(input)
			if input.val() == '' then continue
			variant_values[counter++] = input.val()

		default_input = $('#ProductAttribute_default')
		default_val = default_input.val() || 0
		if default_input.is('select')
			if field_type_input.val() == 'checkbox'
				$('option', default_input).remove()
			else
				$('option', default_input).remove()
				default_input.append("<option value=''></option>")
			for own i, value of variant_values
				default_input.append("<option value='"+i+"'>"+value+"</option>")
			default_input.val default_val
	update_variants()


	check_field_type = (e) ->
		default_input = $('#ProductAttribute_default')
		if field_type_input.val() == 'string' || field_type_input.val() == 'text'
			if default_input.is('select')
				default_input.replaceWith('<input class="span8" type="text" id="ProductAttribute_default" name="ProductAttribute[default]" />')
			variants_block.hide()
		else
			if default_input.is('input')
				default_input.replaceWith('<select class="span8" type="text" id="ProductAttribute_default" name="ProductAttribute[default]" />')
			if field_type_input.val() == 'checkbox'
				checkbox_variants_rows.show()
				variants_rows.hide()
				add_variant_button.hide()
			else
				checkbox_variants_rows.hide()
				variants_rows.show()
				add_variant_button.show()
			update_variants()
			variants_block.show()
	check_field_type()


	field_type_input.change check_field_type


	add_variant_button.click (e) ->
		variants_rows.append('<div class="control-row"><input type="text" /></div>').focus();
		$('.control-row', variants_rows).last().find('input').focus()
		false


	variants_block.add(checkbox_variants_rows).on 'keyup', '.control-row input', (e) ->
		update_variants()
		variants_input.val JSON.stringify variant_values