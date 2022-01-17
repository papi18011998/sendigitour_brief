/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Icon, Spinner, Modal, Button, ButtonGroup } = wp.components;
const { useState } = wp.element;

export default function ({
  preset,
  index,
  isActive,
  remove,
  setPreset,
  onEdit,
}) {
  const [loading, setLoading] = useState(false);
  const [confirmOpen, setConfirmOpen] = useState(false);

  const openConfirm = () => setConfirmOpen(true);
  const closeConfirm = () => setConfirmOpen(false);

  const untrashPreset = async () => {};

  const trashPreset = async () => {
    // remove unsaved
    if (!preset.id) {
      remove(preset);
      return;
    }

    try {
      setLoading(true);
      let deleted = await wp.apiFetch({
        method: "POST",
        url: wp.url.addQueryArgs(
          `${prestoPlayer.root}${prestoPlayer.prestoVersionString}preset/${preset.id}`,
          { _method: "DELETE" }
        ),
      });
      if (deleted) {
        remove(preset);
        wp.data.dispatch("core/notices").createNotice(
          "success", // Can be one of: success, info, warning, error.
          __("Preset trashed.", "presto-player"),
          {
            type: "snackbar",
            isDismissible: true,
            // actions: [
            //   {
            //     label: __("Undo", "presto-player"),
            //     onClick: () => {
            //       console.log("untrash");
            //     },
            //   },
            // ],
          }
        );
      }
    } catch (e) {
      console.error(e);
      if (e?.message) {
        wp.data.dispatch("core/notices").createNotice(
          "error", // Can be one of: success, info, warning, error.
          e.message,
          {
            type: "snackbar",
            isDismissible: true, // Whether the user can dismiss the notice.
          }
        );
      }
    } finally {
      setConfirmOpen(false);
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div
        className="block-editor-block-styles__item"
        role="button"
        tabIndex={index}
        aria-label="Fill"
        style={{ color: isActive ? "var(--wp-admin-theme-color)" : "inherit" }}
      >
        <div
          className="block-editor-block-styles__item-preview"
          style={{
            border: isActive
              ? "2px solid var(--wp-admin-theme-color)"
              : "2px solid #e3e3e3",
            margin: 0,
            display: "flex",
            "align-items": "center",
            "justify-content": "center",
          }}
        >
          <Spinner />
        </div>
      </div>
    );
  }

  return (
    <div
      className={`block-editor-block-styles__item presto-preset-${preset.slug}`}
      onClick={() => {
        setPreset(preset);
      }}
      role="button"
      tabIndex={index}
      aria-label="Fill"
      style={{ color: isActive ? "var(--wp-admin-theme-color)" : "inherit" }}
    >
      <div
        className="block-editor-block-styles__item-preview"
        style={{
          border: isActive
            ? "2px solid var(--wp-admin-theme-color)"
            : "2px solid #e3e3e3",
          margin: 0,
        }}
      >
        <div
          style={{
            textAlign: "center",
            width: "100%",
            color: isActive ? "var(--wp-admin-theme-color)" : "inherit",
            opacity: isActive ? 1 : 0.75,
          }}
        >
          {preset?.icon ? (
            <Icon
              icon={preset?.icon}
              style={{
                fontSize: "30px",
                width: "30px",
                height: "30px",
              }}
            />
          ) : (
            <h1 style={{ opacity: 0.75 }}>
              {preset?.name?.charAt(0)?.toUpperCase() || "Untitled"}
            </h1>
          )}
        </div>
      </div>
      <div
        className="block-editor-block-styles__item-label"
        style={{ fontWeight: "bold", textTransform: "capitalize" }}
      >
        {preset?.name || "Untitled"}
      </div>

      {!preset?.is_locked && (
        <div className="block-editor-block-styles__item-edit">
          <div
            className="block-editor-block-styles__item-edit-icon"
            onClick={onEdit}
          >
            <Icon icon="edit" />
          </div>
          <div
            className="block-editor-block-styles__item-edit-icon"
            onClick={openConfirm}
          >
            <Icon icon="trash" />
          </div>
        </div>
      )}

      {confirmOpen && (
        <Modal
          title={__("Trash Preset?", "presto-player")}
          onRequestClose={closeConfirm}
          style={{ maxWidth: "250px" }}
        >
          <p>
            <strong>{__("Warning!", "presto-player")} </strong>
            {__(
              "Any videos assigned to this preset will automatically use the default preset.",
              "presto-player"
            )}
          </p>

          <ButtonGroup>
            <Button
              isDestructive
              onClick={trashPreset}
              style={{ margin: "0 4px" }}
            >
              {__("Trash", "presto-player")}
            </Button>
            <Button
              isTertiary
              onClick={closeConfirm}
              style={{ margin: "0 4px", boxShadow: "none" }}
            >
              {__("Cancel", "presto-player")}
            </Button>
          </ButtonGroup>
        </Modal>
      )}
    </div>
  );
}
