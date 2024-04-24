import { Base64, generateId } from "../services";
import WaAdvancedPillList from "../wa_advanced_pill_list";
import WaPersonalizedComponent from "../wa_personalized_component";
import WaResetOptionsHeader from "../wa_reset_options_header";
import WaTabs from "../wa_tabs";
import "./style.scss";

class WaBlocksSequence extends WaPersonalizedComponent {
    constructor(props) {
        super(props);
        this.onPersonalizedChanged = this.onPersonalizedChanged.bind(this);
        this.sequenceId = generateId();
    }
    onPersonalizedChanged(personalized, commonValue) {
        if (!personalized) this.sequenceId = generateId();
        this.persinalizingSetupDelay = true;
        setTimeout(() => {
            this.persinalizingSetupDelay = false;
        });
    }
    render() {
        let config = null;
        let reordered_choices = null;

        if (
            this.props.field.personal_mode === true &&
            (this.props.field.value == "[!discard personal]" || !this.state.usePersonalSetting)
        ) {
            config = JSON.parse(this.props.field.common_value);
            reordered_choices = this.props.field.common_reordered_choices;
        } else {
            config = JSON.parse(this.props.value ? this.props.value : this.props.field.default_value);
            reordered_choices = this.props.field.reordered_choices ?? this.props.field.common_reordered_choices;
        }

        const {
            field: { label, context, description },
        } = this.props;
        const onChange = (sequence, blocks) => {
            if (!this.persinalizingSetupDelay) this.setPersonalized();
            blocks = blocks ?? config.blocks;
            sequence = sequence ?? config.sequence;
            if (this.props.onChange) this.props.onChange(this.props.id, JSON.stringify({ sequence, blocks }));
        };
        const onBlockChange = (index, value) => {
            onChange(
                undefined,
                config.blocks.map((v, i) => (i == index ? Base64.encode(value) : v))
            );
        };
        return (
            <div className={this.getPersonalizedClass()} ref={this.rootRef}>
                <WaResetOptionsHeader
                    label={label}
                    {...this.resetProps}
                    onTabReset={undefined}
                    onFullReset={undefined}
                    context={context}
                >
                    <input
                        type="hidden"
                        id={this.props.id}
                        name={this.props.name}
                        value={this.getValueToSave(this.props.value)}
                    />
                    <WaAdvancedPillList
                        key={this.sequenceId}
                        field={{
                            reordered_choices,
                        }}
                        value={config.sequence}
                        onChange={(id, seq) => {
                            onChange(seq);
                        }}
                    />
                    <WaTabs>
                        {{
                            "Блок 1": (
                                <textarea
                                    className="blocks_sequence_text"
                                    placeholder="Введите HTML или шорткоды..."
                                    onChange={(e) => onBlockChange(0, e.target.value)}
                                    value={Base64.decode(config.blocks[0])}
                                ></textarea>
                            ),
                            "Блок 2": (
                                <textarea
                                    className="blocks_sequence_text"
                                    placeholder="Введите HTML или шорткоды..."
                                    onChange={(e) => onBlockChange(1, e.target.value)}
                                    value={Base64.decode(config.blocks[1])}
                                ></textarea>
                            ),
                            "Блок 3": (
                                <textarea
                                    className="blocks_sequence_text"
                                    placeholder="Введите HTML или шорткоды..."
                                    onChange={(e) => onBlockChange(2, e.target.value)}
                                    value={Base64.decode(config.blocks[2])}
                                ></textarea>
                            ),
                        }}
                    </WaTabs>
                </WaResetOptionsHeader>
            </div>
        );
    }
}

export default WaBlocksSequence;
