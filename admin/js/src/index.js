import { registerFormatType, toggleFormat } from "@wordpress/rich-text";
import { RichTextToolbarButton } from "@wordpress/block-editor";
import { BlockControls } from "@wordpress/block-editor";
import { ToolbarGroup, ToolbarButton } from "@wordpress/components";
import "./style.css";

const redLinkIcon = (
  <svg
    id="Layer_1"
    data-name="Layer 1"
    xmlns="http://www.w3.org/2000/svg"
    width="86.66mm"
    height="87.07mm"
    viewBox="0 0 245.66 246.82"
  >
    {" "}
    <defs>
      {" "}
      <style
        dangerouslySetInnerHTML={{
          __html:
            " .cls-1 { fill: #fad10b; } .cls-1, .cls-2, .cls-3 { stroke-width: 0px; } .cls-2 { fill: #097752; } .cls-3 { fill: #e9242b; } ",
        }}
      />{" "}
    </defs>{" "}
    <ellipse
      className="cls-1"
      cx="122.83"
      cy="123.41"
      rx="122.83"
      ry="123.41"
    />{" "}
    <g>
      {" "}
      <path
        className="cls-2"
        d="M223.95,85.02l-28.33,35.98,23.07,38.03h-17.94l-15.7-28.75-5.91,7.54v21.21h-14.68v-66.07h14.68v28.75l36.71-53.75c-18.79-32.01-53.41-53.49-93.01-53.49C63.19,14.48,14.85,63.17,14.85,123.23s48.34,108.75,107.98,108.75,107.98-48.69,107.98-108.75c0-13.45-2.43-26.32-6.86-38.21Z"
      />{" "}
      <path className="cls-2" d="M215.84,67.96h0s.02.05.04.07l-.03-.07Z" />{" "}
      <path className="cls-2" d="M223.95,85.01l-.03-.07s.02.05.03.08h0Z" />{" "}
    </g>{" "}
    <polygon
      className="cls-1"
      points="119.4 92.62 101.77 92.62 101.77 159.03 115.4 159.03 115.4 110.38 141.02 159.03 156.77 159.03 156.77 92.62 142.9 92.62 144.02 139.38 119.4 92.62"
    />{" "}
    <rect className="cls-1" x="79.02" y="92.62" width="15.5" height="66.4" />{" "}
    <polygon
      className="cls-1"
      points="33.9 92.62 49.02 92.62 49.02 146.12 72.02 146.12 72.02 159.03 33.9 159.03 33.9 92.62"
    />{" "}
    <path
      className="cls-3"
      d="M96.36,73.33c0,5.52-4.4,10-9.83,10s-9.83-4.48-9.83-10,4.4-10,9.83-10,9.83,4.48,9.83,10Z"
    />{" "}
  </svg>
);
function slugify(text) {
  return text
    .toString()
    .toLowerCase()
    .replace(/\s+/g, "-") // Replace spaces with -
    .replace(/[^\w\-]+/g, "") // Remove all non-word chars
    .replace(/\-\-+/g, "-") // Replace multiple - with single -
    .replace(/^-+/, "") // Trim - from start of text
    .replace(/-+$/, ""); // Trim - from end of text
}
const MyCustomButton = ({ isActive, onChange, value }) => {
  const previousAttributes = value.activeFormats.find(
    (format) => format.type === "kibru/red-link",
  );
  const selectedText =
    value.text.substr(value.start, value.end - value.start) ?? "";

  const previousUrl = previousAttributes
    ? previousAttributes.attributes.url
    : slugify(selectedText + Math.random(1000, 9999));
  // const previousData = previousAttributes
  //   ? previousAttributes.attributes.data
  //   : "-";

  return (
    <BlockControls>
      <ToolbarGroup>
        <ToolbarButton
          icon={redLinkIcon}
          title="Red Link"
          onClick={() => {
            onChange(
              toggleFormat(value, {
                type: "kibru/red-link",
                attributes: {
                  url: previousUrl,
                  // data: previousData,
                },
              }),
            );
          }}
          isActive={isActive}
        />
      </ToolbarGroup>
    </BlockControls>
  );
};

registerFormatType("kibru/red-link", {
  title: "Red Link",
  tagName: "red-link",
  className: "red-link",
  edit: MyCustomButton,
  attributes: {
    url: "href",
    // data: "data-page-exists",
  },
});
