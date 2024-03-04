/**
 * External dependencies.
 */
import { registerFieldType } from "@carbon-fields/core";

/**
 * Internal dependencies.
 */
import WaAdvancedPillList from "./wa_advanced_pill_list";
import WaToggle from "./wa_toggle";
import WaSchemaInfo from "./wa_schema_info";
import WaToggleButtonsList from "./wa_toggle_buttons_list";
import WaHtml from "./wa_html";
import WaText from "./wa_text";
import WaActiveTabSaver from "./wa_active_tab_saver";
import WaProfiledSettings from "./wa_profiled_settings";

registerFieldType("wa_active_tab_saver", WaActiveTabSaver);
registerFieldType("wa_html", WaHtml);
registerFieldType("wa_text", WaText);
registerFieldType("wa_advanced_pill_list", WaAdvancedPillList);
registerFieldType("wa_toggle", WaToggle);
registerFieldType("wa_schema_info", WaSchemaInfo);
registerFieldType("wa_toggle_button_list", WaToggleButtonsList);
registerFieldType("wa_profiled_settings", WaProfiledSettings);
