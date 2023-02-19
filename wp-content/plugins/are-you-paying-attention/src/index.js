import "./index.scss"
import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker} from "@wordpress/components"
import {InspectorControls, BlockControls, AlignmentToolbar, useBlockProps} from "@wordpress/block-editor"
// import {ChromePicker} from "react-color"
// comentei esse import pq nao gostei do resultado do professor com esse color picker

(function() {
    let locked = false

    wp.data.subscribe(function() {
        const results = wp.data.select("core/block-editor").getBlocks().filter(function(block) {
            return block.name == "myplugin/aypa" && block.attributes.correctAnswer == undefined
        })

        if (results.length && locked == false) {
            locked = true
            wp.data.dispatch("core/editor").lockPostSaving("noanswer")
        }

        if (!results.length && locked) {
            locked = false
            wp.data.dispatch("core/editor").unlockPostSaving("noanswer")
        }
    });
})()

wp.blocks.registerBlockType("myplugin/aypa", {
    title: "Are You Paying Attention?",
    icon: "visibility",
    category: "common",
    attributes: {
        question: {type: "string"},
        answers: {type: "array", default: [""]},
        correctAnswer: {type: "number", default: undefined},
        bgColor: {type: "string", default: "#EBEBEB"},
        theAlignment: {type: "string", default: "left"}
    },
    description: "Give your audience a chance to prove their comprehension.",
    example: {
        attributes: {
            question: "What's the color of the Napoleon White Horse?",
            answers: ["White", "Brown", "Yellow", "Zebra"],
            correctAnswer: 1,
            theAlignment: "center",
            bgColor: "#303030"
        }
    },
    edit: EditComponent,
    save: function (props) {
        return null
    }
});

function EditComponent(props) {
    const blockProps = useBlockProps({
        className: "paying-attention-edit-block",
        style: {backgroundColor: props.attributes.bgColor}
    })

    function updateQuestion(value) {
        props.setAttributes({question: value});
    }

    function deleteAnswer(indexToDelete) {
        const newAnswers = props.attributes.answers.filter(function(x, index) {
            return index != indexToDelete;
        })
        props.setAttributes({answers: newAnswers})

        if (indexToDelete == props.attributes.correctAnswer) {
            props.setAttributes({correctAnswer: undefined})
        }
    }

    function markAsCorrect(index) {
        props.setAttributes({correctAnswer: index})
    }

    return (
        <div {...blockProps}>
            <BlockControls>
                <AlignmentToolbar value={props.attributes.theAlignment} onChange={(x) => props.setAttributes({theAlignment: x})} />
            </BlockControls>
            <InspectorControls>
                <PanelBody title="Background Color" initialOpen={true}>
                    <PanelRow>
                        <ColorPicker color={props.attributes.bgColor} onChangeComplete={(x) => props.setAttributes({bgColor: x.hex})} />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <TextControl label="Question:" value={props.attributes.question} onChange={updateQuestion} style={{fontSize: "20px"}} />
            <p className="answers-label">Answers:</p>
            
            {props.attributes.answers.map(function (answer, index) {
                return (
                    <Flex>
                        <FlexBlock>
                            <TextControl autoFocus={answer == undefined} value={answer} onChange={newValue => {
                                const newAnswers = props.attributes.answers.concat([])
                                newAnswers[index] = newValue
                                props.setAttributes({answers: newAnswers})
                            }} />
                        </FlexBlock>
                        <FlexItem>
                            <Button onClick={() => markAsCorrect(index)}>
                                <Icon className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"} />
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button isLink className="attention-delete" onClick={() => deleteAnswer(index)}>Delete</Button>
                        </FlexItem>
                    </Flex>
                )
            })}

            <Button isPrimary onClick={() => {
                props.setAttributes({answers: props.attributes.answers.concat([undefined])})
            }} style={{marginTop: "20px"}}>Add another answer</Button>
        </div>
    );
}