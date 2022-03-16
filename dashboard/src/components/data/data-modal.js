import CloseIcon from "@mui/icons-material/Close";
import DateTimePicker from "@mui/lab/DateTimePicker";
import Button from "@mui/material/Button";
import Dialog from "@mui/material/Dialog";
import DialogActions from "@mui/material/DialogActions";
import DialogContent from "@mui/material/DialogContent";
import DialogTitle from "@mui/material/DialogTitle";
import IconButton from "@mui/material/IconButton";
import MenuItem from "@mui/material/MenuItem";
import TextField from "@mui/material/TextField";
import { format } from "date-fns";
import React, { useEffect, useState } from "react";

const Form = ({ fields, data, setData, primaryKey }) => {
  const handleChange = (event) => {
    setData({
      ...data,
      [event.target.name]: event.target.value,
    });
  };

  const handleChange2 = (name, value) => {
    setData({
      ...data,
      [name]: value,
    });
  };

  return (
    <form>
      {fields.map((field) => {
        const sx =
          (!field.options.display && field.name == primaryKey) || field.hideInModal
            ? { display: "none" }
            : null;
        switch (field.type) {
          case "text":
            return (
              <TextField
                key={field.name}
                fullWidth
                label={field.label}
                margin="normal"
                name={field.name}
                onChange={handleChange}
                type="text"
                value={data[field.name] ? data[field.name] : ""}
                variant="outlined"
                required={field.required}
                sx={sx}
              />
            );
          case "datetime":
            return (
              <div key={field.name}>
                <DateTimePicker
                  renderInput={(props) => (
                    <TextField
                      {...props}
                      fullWidth
                      required={field.required}
                      margin="normal"
                      variant="outlined"
                    />
                  )}
                  label={field.label}
                  value={data[field.name] ? data[field.name] : null}
                  onChange={(newValue) => {
                    try {
                      handleChange2(field.name, format(newValue, "yyyy-MM-dd HH:mm"));
                    } catch (e) {
                      handleChange2(field.name, null);
                    }
                  }}
                  inputFormat="dd.MM.yyyy HH:mm"
                  mask="__.__.____ __:__"
                  sx={sx}
                />
              </div>
            );
          case "textarea":
            return (
              <TextField
                key={field.name}
                fullWidth
                multiline
                label={field.label}
                margin="normal"
                name={field.name}
                onChange={handleChange}
                value={data[field.name] ? data[field.name] : ""}
                variant="outlined"
                required={field.required}
                sx={sx}
              />
            );
          case "number":
            return (
              <TextField
                key={field.name}
                fullWidth
                label={field.label}
                margin="normal"
                name={field.name}
                onChange={handleChange}
                type="number"
                value={data[field.name] ? data[field.name] : ""}
                variant="outlined"
                required={field.required}
                sx={sx}
              />
            );
          case "dropdown":
            return (
              <TextField
                key={field.name}
                fullWidth
                label={field.label}
                margin="normal"
                name={field.name}
                onChange={handleChange}
                value={data[field.name] ? data[field.name] : ""}
                variant="outlined"
                required={field.required}
                select
                sx={sx}
              >
                {field.dropdownItems.map((option) => (
                  <MenuItem key={option.value} value={option.value}>
                    {option.label}
                  </MenuItem>
                ))}
              </TextField>
            );
          default:
            return (
              <TextField
                key={field.name}
                fullWidth
                label={field.label}
                margin="normal"
                name={field.name}
                onChange={handleChange}
                type={field.type}
                value={data[field.name] ? data[field.name] : ""}
                variant="outlined"
                required={field.required}
                sx={sx}
              />
            );
        }
      })}
    </form>
  );
};

const BootstrapDialogTitle = (props) => {
  const { children, onClose, ...other } = props;

  return (
    <DialogTitle sx={{ m: 0, p: 2 }} {...other}>
      {children}
      {onClose ? (
        <IconButton
          aria-label="close"
          onClick={onClose}
          sx={{
            position: "absolute",
            right: 8,
            top: 8,
            color: (theme) => theme.palette.grey[500],
          }}
        >
          <CloseIcon />
        </IconButton>
      ) : null}
    </DialogTitle>
  );
};

const DataModal = ({ title, fields, open, setOpen, initialData, onSave, primaryKey }) => {
  const [values, setValues] = useState(initialData);
  useEffect(() => {
    setValues(initialData);
  }, [initialData]);

  const handleClickSave = () => {
    if (Object.is(values, initialData) && Object.keys(values).length > 0) {
      setOpen(false);
    } else {
      onSave(values);
    }
  };

  return (
    <Dialog
      onClose={() => {
        setOpen(false);
      }}
      open={open}
      fullWidth={true}
    >
      <BootstrapDialogTitle onClose={() => setOpen(false)}>{title}</BootstrapDialogTitle>
      <DialogContent dividers>
        <Form fields={fields} data={values} setData={setValues} primaryKey={primaryKey} />
      </DialogContent>
      <DialogActions>
        <Button autoFocus onClick={() => setOpen(false)}>
          Abbrechen
        </Button>
        <Button autoFocus color="primary" variant="contained" onClick={handleClickSave}>
          Speichern
        </Button>
      </DialogActions>
    </Dialog>
  );
};

export default DataModal;
