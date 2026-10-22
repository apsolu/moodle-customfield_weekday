<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace customfield_weekday;

/**
 * Base class for custom fields controllers
 *
 * This class is a wrapper around the persistent field class that allows to define the field
 * configuration
 *
 * Contrôle le formulaire d'édition d'une instance de champ personnalisé dans l'administration.
 * Enregistre un entier représentant l'identifiant de l'activité. À l'affichage, le nom de l'activité est affiché.
 *
 * @package customfield_weekday
 * @copyright 2026 Université Rennes 2
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class field_controller extends \core_customfield\field_controller {
    /** @var array Liste des jours de la semaine en anglais, indexé par jour de la semaine. */
    public static $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    /**
     * Add fields for editing a date field.
     *
     * @param \MoodleQuickForm $mform
     */
    public function config_form_definition(\MoodleQuickForm $mform) {
        // Add elements.
        $mform->addElement(
            'header',
            'header_default_settings',
            get_string('default_field_settings', 'customfield_weekday')
        );
        $mform->setExpanded('header_default_settings', true);

        $mform->addElement(
            'select',
            'configdata[defaultvalue]',
            get_string('defaultvalue', 'core_customfield'),
            $this->get_options()
        );
        $mform->setType('configdata[defaultvalue]', PARAM_TEXT);
    }

    /**
     * Validate the data from the config form.
     *
     * @param array $data
     * @param array $files
     * @return array associative array of error messages
     */
    public function config_form_validation(array $data, $files = []): array {
        $errors = [];

        $options = $this->get_options();
        $defaultvalue = $data['configdata']['defaultvalue'];
        if (isset($options[$defaultvalue]) === false) {
            $errors['configdata[defaultvalue]'] = get_string('the_selected_value_is_invalid', 'customfield_weekday');
        }

        return $errors;
    }

    /**
     * Return configured field options
     *
     * @return array
     */
    public function get_options(): array {
        $weekdays = ['' => ''];

        foreach (self::$days as $numweekday => $weekday) {
            $weekdays[$numweekday] = get_string($weekday, 'customfield_weekday');
        }

        return $weekdays;
    }

    /**
     * Locate the value parameter in the field options array, and return it's index
     *
     * @param string $value
     * @return int
     */
    public function parse_value(string $value) {
        return (int) array_search($value, $this->get_options());
    }
}
